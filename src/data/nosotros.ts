import type { Stat, Valor, Diferenciador, MisionVision } from '../types';

export const STATS_HERO: Stat[] = [
  { count: 30, suffix: '+',  label: 'Años de experiencia'  },
  { count: 60, suffix: 'T',  label: 'Producción máx/día'   },
  { count: 100, suffix: '%', label: 'Fabricación propia'   },
];

export const STATS_NOSOTROS: Stat[] = [
  { count: 30, suffix: '+',   label: 'Años en el mercado'       },
  { count: 60, suffix: 'T',   label: 'Cap. máx. por equipo'     },
  { count: 3,  suffix: 'gen', label: 'Generaciones de clientes' },
];

export const DIFERENCIADORES: Diferenciador[] = [
  {
    label: 'Fabricación propia',
    headline: 'Taller en Puebla, control total',
    text: 'Diseñamos y construimos nuestros equipos en instalaciones propias. Cada máquina es calculada por nuestros ingenieros y fabricada con materiales certificados — sin subcontratar la producción.',
    ico: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
  },
  {
    label: 'Distribución especializada',
    headline: 'Las mejores marcas, con asesoría real',
    text: 'Distribuimos equipos y componentes de marcas líderes en refrigeración industrial. Nuestra experiencia técnica nos permite recomendarte el producto correcto según tu operación, no según el margen.',
    ico: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
  },
  {
    label: 'Servicio integral',
    headline: 'Del proyecto al mantenimiento',
    text: 'Instalamos, ponemos en marcha y damos mantenimiento a los equipos que vendemos o fabricamos. Una sola empresa responsable de principio a fin — sin señalar a terceros cuando algo falla.',
    ico: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
  },
];

export const MISION_VISION: MisionVision[] = [
  {
    label: 'Misión',
    headline: 'Equipos que hacen rentable tu inversión',
    text: 'Proveer soluciones de refrigeración industrial — fabricadas o distribuidas — con la calidad, eficiencia y respaldo técnico que permitan a nuestros clientes operar con confianza desde el primer día.',
    ico: 'M13 10V3L4 14h7v7l9-11h-7z',
  },
  {
    label: 'Visión',
    headline: 'La empresa de referencia en México',
    text: 'Ser el proveedor más confiable de México en refrigeración industrial: con capacidad de fabricar lo que el mercado necesita y de distribuir lo mejor del mundo cuando sea la opción correcta para el cliente.',
    ico: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
  },
];

export const VALORES: Valor[] = [
  { v: 'Trabajo en equipo', ico: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
  { v: 'Responsabilidad',   ico: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' },
  { v: 'Lealtad',           ico: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' },
  { v: 'Sostenibilidad',    ico: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
  { v: 'Innovación',        ico: 'M13 10V3L4 14h7v7l9-11h-7z' },
  { v: 'Transparencia',     ico: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' },
];
