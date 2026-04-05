export interface NavLink {
  href: string;
  label: string;
}

export interface Stat {
  count: number;
  suffix: string;
  label: string;
}

export interface Valor {
  v: string;
  ico: string;
}

export interface Diferenciador {
  label: string;
  headline: string;
  text: string;
  ico: string;
}

export interface MisionVision {
  label: string;
  headline: string;
  text: string;
  ico: string;
}

export interface EquipoSpec {
  k: string;
  v: string;
}

export interface Equipo {
  id: string;
  num: string;
  title: string;
  sub: string;
  desc: string;
  specs: EquipoSpec[];
  tag: string;
}

export interface Paso {
  num: string;
  title: string;
  desc: string;
  icon: string;
}
