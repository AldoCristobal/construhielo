/**
 * Convierte todas las imágenes PNG/JPG a WebP
 * Uso: node scripts/to-webp.mjs
 */

import sharp from 'sharp';
import { readdir, stat } from 'fs/promises';
import { join, extname, basename } from 'path';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __dirname = dirname(fileURLToPath(import.meta.url));
const PUBLIC    = join(__dirname, '..', 'public', 'images');

const SKIP = [
  'logo-dark.png',    // logos se usan en emails, mantener PNG
  'logo-light.png',
  'logo-icon-dark.png',
  'logo-icon-light.png',
];

async function findImages(dir) {
  const entries = await readdir(dir, { withFileTypes: true });
  const files   = [];
  for (const e of entries) {
    const full = join(dir, e.name);
    if (e.isDirectory()) {
      files.push(...await findImages(full));
    } else if (/\.(png|jpg|jpeg)$/i.test(e.name) && !SKIP.includes(e.name)) {
      files.push(full);
    }
  }
  return files;
}

const images = await findImages(PUBLIC);
let converted = 0, skipped = 0;

for (const src of images) {
  const webpPath = src.replace(/\.(png|jpg|jpeg)$/i, '.webp');
  const srcStat  = await stat(src);

  const info = await sharp(src)
    .webp({ quality: 82, effort: 6 })
    .toFile(webpPath);

  const reduction = (((srcStat.size - info.size) / srcStat.size) * 100).toFixed(0);
  console.log(`✓ ${basename(src)} → ${basename(webpPath)}  ${(srcStat.size/1024).toFixed(0)}KB → ${(info.size/1024).toFixed(0)}KB  (-${reduction}%)`);
  converted++;
}

console.log(`\n✅ ${converted} imágenes convertidas a WebP`);
