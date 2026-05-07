/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html,js,ts}'],
  theme: {
    extend: {
      colors: {
        whatsapp: '#25d366',
        paper: {
          50:  '#f8fbfd',
          100: '#eef5f9',
          200: '#daeaf3',
        },
        steel: {
          900: '#0b1e2d',
          800: '#102840',
          700: '#163756',
          600: '#1c4870',
          500: '#235a8c',
          400: '#2e74b0',
          300: '#4a92cc',
          200: '#7db8e0',
          100: '#b8d9f0',
          50:  '#e2f0f9',
        },
        ice: {
          600: '#0891b2',
          500: '#06a7cc',
          400: '#22bbd4',
          300: '#4dcde0',
          200: '#85deed',
          100: '#c8f2f9',
        },
      },
      fontFamily: {
        display: ['"Bebas Neue"', 'cursive'],
        mono:    ['"Space Mono"', 'monospace'],
        body:    ['"Plus Jakarta Sans"', 'sans-serif'],
      },
      keyframes: {
        fadeUp:   { '0%': { opacity: '0', transform: 'translateY(26px)' }, '100%': { opacity: '1', transform: 'none' } },
        fadeIn:   { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
        slideR:   { '0%': { opacity: '0', transform: 'translateX(-28px)' }, '100%': { opacity: '1', transform: 'none' } },
        slideL:   { '0%': { opacity: '0', transform: 'translateX(28px)' }, '100%': { opacity: '1', transform: 'none' } },
        scaleIn:  { '0%': { opacity: '0', transform: 'scale(0.93)' }, '100%': { opacity: '1', transform: 'none' } },
        spinSlow: { 'to': { transform: 'rotate(360deg)' } },
        spinRev:  { 'to': { transform: 'rotate(-360deg)' } },
        floatY:   { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-9px)' } },
        blink:    { '0%,100%': { opacity: '1' }, '50%': { opacity: '0.25' } },
        shimmer:  { '0%': { backgroundPosition: '-600px 0' }, '100%': { backgroundPosition: '600px 0' } },
      },
      animation: {
        'fade-up':   'fadeUp 0.72s ease forwards',
        'fade-in':   'fadeIn 0.6s ease forwards',
        'slide-r':   'slideR 0.65s ease forwards',
        'slide-l':   'slideL 0.65s ease forwards',
        'scale-in':  'scaleIn 0.6s ease forwards',
        'spin-slow': 'spinSlow 14s linear infinite',
        'spin-rev':  'spinRev 10s linear infinite',
        'float':     'floatY 5s ease-in-out infinite',
        'blink':     'blink 2.2s ease-in-out infinite',
      },
      backgroundImage: {
        'grid-light': `linear-gradient(rgba(34,107,170,0.07) 1px, transparent 1px),
                       linear-gradient(90deg, rgba(34,107,170,0.07) 1px, transparent 1px)`,
      },
      backgroundSize: {
        'grid': '44px 44px',
      },
    },
  },
  plugins: [],
};
