export const countToStudy = (total, limit) => {
  const n = Math.floor(Number(limit))
  return n > 0 ? Math.min(n, total) : total
}

export const pickCards = (cards, limit) => 
  cards.slice(0, countToStudy(cards.length, limit))
