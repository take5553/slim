import { loadCards } from '../store.js'

export const study = () => ({
  phase: 'loading',
  cards: [],
  step: 0,

  async init() {
    this.cards = await loadCards()
    this.phase = 'study'
  },

  get index() {
    return Math.floor(this.step / 2)
  },
  get flipped() {
    return this.step % 2 === 1
  },
  get card() {
    return this.cards[this.index]
  },
  get isFirstStep() {
    return this.step === 0
  },
  get isLastStep() {
    return this.step === this.cards.length * 2 - 1
  },
  get progress() {
    return `${this.index + 1} / ${this.cards.length}`
  },
  get side() {
    return this.flipped ? '裏' : '表'
  },
  get faceText() {
    return this.flipped ? this.card.back_md : this.card.front_md
  },
  get faceClass() {
    return 'face-' + (this.flipped ? this.card.back_size : this.card.front_size)
  },

  forward() {
    if (this.isLastStep) return
    this.goTo(this.step + 1)
  },
  back() {
    if (this.isFirstStep) return
    this.goTo(this.step - 1)
  },
  goTo(step) {
    this.step = step
    this.$refs.card.scrollTop = 0
  }
})