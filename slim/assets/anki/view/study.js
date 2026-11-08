import { loadCards } from '../store.js'

export const study = () => ({
  phase: 'loading',
  cards: [],
  index: 0,
  flipped: false,

  async init() {
    this.cards = await loadCards()
    this.phase = 'study'
  },

  get card() {
    return this.cards[this.index]
  },
  get isFirst() {
    return this.index === 0
  },
  get isLast() {
    return this.index === this.cards.length - 1
  },
  get progress() {
    return `${this.index + 1} / ${this.cards.length}`
  },
  get side() {
    return this.flipped ? this.card.back_md : this.card.front_md
  },
  get faceText() {
    return this.flipped ? this.card.back_md : this.card.front_md
  },
  get faceClass() {
    return 'face-' + (this.flipped ? this.card.back_size : this.card.front_size)
  },

  flip() {
    this.flipped = !this.flipped
  },
  prev() {
    if (this.isFirst) return
    this.index--
    this.flipped = false
  },
  next() {
    if (this.isLast) return
    this.index++
    this.flipped = false
  },
})