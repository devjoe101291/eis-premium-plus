const hashString = (value: string) => {
  let hash = 0
  for (let i = 0; i < value.length; i += 1) {
    hash = (hash << 5) - hash + value.charCodeAt(i)
    hash |= 0
  }
  return Math.abs(hash)
}

export const getAvatarColors = (seed: string) => {
  const safeSeed = seed?.trim() || 'user'
  const hash = hashString(safeSeed)
  const hue = hash % 360
  const hueOffset = 35 + (hash % 50)
  const hue2 = (hue + hueOffset) % 360

  return {
    primary: `hsl(${hue} 70% 52%)`,
    secondary: `hsl(${hue2} 70% 45%)`,
    text: `hsl(${hue} 70% 35%)`,
    soft: `hsl(${hue} 70% 96%)`
  }
}
