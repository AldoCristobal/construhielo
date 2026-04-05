import type { Equipo } from '../types';

export const EQUIPOS: Equipo[] = [
  {
    id:    'rolito',
    num:   '01',
    title: 'Máquina de Rolito',
    sub:   'Hielo tubular · 1 a 60 T/día',
    desc:  'Producción ininterrumpida de hielo cilíndrico. Estructura en acero inoxidable de primera calidad, diseñada para operar 24/7 en climas extremos y entornos industriales. Ideal para pequeñas y grandes industrias, adaptándonos a tus necesidades.',
    specs: [
      { k: 'Capacidad',    v: '1 – 60 T / 24h'  },
      { k: 'Refrigerante', v: 'NH₃ o Freon'      },
      { k: 'Material',     v: 'Acero estructural' },
      { k: 'Operación',    v: 'Continua 24/7'     },
    ],
    tag: 'Producción de hielo',
  },
  {
    id:    'barras',
    num:   '02',
    title: 'Tanque de Barras',
    sub:   'Bloques sólidos 50, 75 y 150 kg',
    desc:  'Tanques para producción de barras macizas de alta pureza. Fabricamos desde el tanque hasta el serpentín de acuerdo a la capacidad y espacio requerido. Moldes galvanizados o en acero inoxidable bajo pedido.',
    specs: [
      { k: 'Moldes',        v: '50 / 75 / 150 kg'  },
      { k: 'Grúa viajera',  v: 'Capacidad a medida' },
      { k: 'Refrigerante',  v: 'NH₃ o Freon'        },
      { k: 'Diseño',        v: 'Capacidad a medida' },
    ],
    tag: 'Hielo en bloque',
  },
  {
    id:    'camara',
    num:   '03',
    title: 'Cámaras Frigoríficas',
    sub:   'Refrigeración y congelación profunda',
    desc:  'Cuartos fríos construidos con panel PIR/PUR o cuarto de concreto aspersado, puerta isotérmica de cierre hermético. Temperatura controlada con precisión para conservación de hielo, alimentos, farmacéuticos, flores o materiales industriales. Dimensiones totalmente a medida.',
    specs: [
      { k: 'Temperatura', v: '−40°C a +8°C'          },
      { k: 'Panel',       v: 'PIR / PUR · Concreto'   },
      { k: 'Puerta',      v: 'Isotérmica'              },
      { k: 'Diseño',      v: 'A medida'                },
    ],
    tag: 'Almacenamiento en frío',
  },
  {
    id:    'evap',
    num:   '04',
    title: 'Condensadores Industriales',
    sub:   'Casco y tubo · Atmosféricos · Evaporativos',
    desc:  'Fabricamos condensadores de casco y tubo y atmosféricos, también manejamos condensadores evaporativos y remotos compatibles con amoniaco y freon. Máxima eficiencia térmica.',
    specs: [
      { k: 'Tipo',        v: 'Casco y tubo · Atmosférico' },
      { k: 'Refrigerante',v: 'NH₃ / Freon'                },
      { k: 'Variantes',   v: 'Evaporativo · Remoto'       },
      { k: 'Fabricación', v: 'Taller propio'              },
    ],
    tag: 'Transferencia de calor',
  },
];
