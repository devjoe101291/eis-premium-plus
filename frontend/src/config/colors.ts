type ColorShade = {
  DEFAULT: string;
  hover: string;
  active: string;
  bg: string;
  text: string;
};

type ColorVariant = {
  light: ColorShade;
  dark: ColorShade;
};

type ColorScheme = {
  primary: ColorVariant;
  secondary: ColorVariant;
  success: ColorVariant;
  danger: ColorVariant;
  warning: ColorVariant;
  info: ColorVariant;
};

export const colors: ColorScheme = {
  primary: {
  light: {
    DEFAULT: '#ff8e48', // main orange
    hover: '#e57b3c',   // darker orange for hover
    active: '#c66933',  // active shade
    bg: '#FFF5F0',      // very light orange background
    text: '#5B21B6',    // violet-700 text for contrast
  },
  dark: {
    DEFAULT: '#ff8e48', // keep same for dark mode main
    hover: '#e57b3c',   // darker hover
    active: '#c66933',  // active
    bg: '#1E1B2F',      // dark violet-tinted background
    text: '#FFF5F0',    // light text for readability
  }
},

secondary: {
  light: {
    DEFAULT: '#644d9f', // complementary violet
    hover: '#533d87',   // darker violet for hover
    active: '#412f6a',  // active shade
    bg: '#F3F0FB',      // soft violet background
    text: '#4C1D95',    // deeper violet text
  },
  dark: {
    DEFAULT: '#7C66C0', // slightly brighter violet for dark mode
    hover: '#644d9f',   // main secondary as hover
    active: '#533d87',  // active
    bg: '#1A1730',      // dark violet background
    text: '#E0DFFF',    // soft text
  }
},

  success: {
    light: {
      DEFAULT: '#059669', // emerald-600 - more refined
      hover: '#047857',   // emerald-700
      active: '#065F46',  // emerald-800
      bg: '#ECFDF5',      // emerald-50 - softer
      text: '#059669',    // emerald-600
    },
    dark: {
      DEFAULT: '#10B981', // emerald-500
      hover: '#34D399',   // emerald-400
      active: '#6EE7B7',  // emerald-300
      bg: '#064E3B',      // emerald-900
      text: '#D1FAE5',    // emerald-100
    }
  },
  danger: {
    light: {
      DEFAULT: '#DC2626', // red-600 - keep for visibility
      hover: '#B91C1C',   // red-700
      active: '#991B1B',  // red-800
      bg: '#FEF2F2',      // red-50
      text: '#DC2626',    // red-600
    },
    dark: {
      DEFAULT: '#F87171', // red-400 - softer for dark mode
      hover: '#FCA5A5',   // red-300
      active: '#FECACA',  // red-200
      bg: '#7F1D1D',      // red-900
      text: '#FEE2E2',    // red-100
    }
  },
  warning: {
    light: {
      DEFAULT: '#D97706', // amber-600 - warmer tone
      hover: '#B45309',   // amber-700
      active: '#92400E',  // amber-800
      bg: '#FFFBEB',      // amber-50 - softer
      text: '#D97706',    // amber-600
    },
    dark: {
      DEFAULT: '#F59E0B', // amber-500
      hover: '#FBBF24',   // amber-400
      active: '#FCD34D',  // amber-300
      bg: '#78350F',      // amber-900
      text: '#FEF3C7',    // amber-100
    }
  },
  info: {
    light: {
      DEFAULT: '#0284C7', // sky-600 - more elegant blue
      hover: '#0369A1',   // sky-700
      active: '#075985',  // sky-800
      bg: '#F0F9FF',      // sky-50 - softer
      text: '#0284C7',    // sky-600
    },
    dark: {
      DEFAULT: '#0EA5E9', // sky-500
      hover: '#38BDF8',   // sky-400
      active: '#7DD3FC',  // sky-300
      bg: '#0C4A6E',      // sky-900
      text: '#E0F2FE',    // sky-100
    }
  }
};

// Gradient definitions for elegant gradients
export const gradients = {
  primary: {
    light: 'linear-gradient(135deg, #5B21B6 0%, #7C3AED 100%)',
    dark: 'linear-gradient(135deg, #7C3AED 0%, #A78BFA 100%)',
  },
  success: {
    light: 'linear-gradient(135deg, #059669 0%, #10B981 100%)',
    dark: 'linear-gradient(135deg, #10B981 0%, #34D399 100%)',
  },
  info: {
    light: 'linear-gradient(135deg, #0284C7 0%, #0EA5E9 100%)',
    dark: 'linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%)',
  },
  card: {
    light: 'linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%)',
    dark: 'linear-gradient(135deg, #1E293B 0%, #0F172A 100%)',
  },
  sidebar: {
    light: 'linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%)',
    dark: 'linear-gradient(180deg, #1E293B 0%, #0F172A 100%)',
  },
};

// Helper function to convert hex to RGB values
export function hexToRgb(hex: string): [number, number, number] {
  // Remove the hash if it exists
  const cleanHex = hex.replace('#', '');
  
  // Parse the hex values
  const r = parseInt(cleanHex.substring(0, 2), 16);
  const g = parseInt(cleanHex.substring(2, 4), 16);
  const b = parseInt(cleanHex.substring(4, 6), 16);
  
  return [r, g, b];
}

// Helper function to convert RGB values to CSS variable format
export function rgbToCssVar(r: number, g: number, b: number): string {
  return `${r} ${g} ${b}`;
}

// Generate CSS variables for the color scheme
export function generateColorVariables(): Record<string, string> {
  const variables: Record<string, string> = {};
  
  Object.entries(colors).forEach(([colorName, variants]) => {
    Object.entries(variants).forEach(([variant, shades]) => {
      Object.entries(shades).forEach(([shade, value]) => {
        const [r, g, b] = hexToRgb(value);
        variables[`--color-${colorName}-${variant}-${shade}`] = rgbToCssVar(r, g, b);
      });
    });
  });

  return variables;
} 