import { loadCards, setStatus } from '../store.js'
import { countToStudy, pickCards } from '../query.js'
import { studyKeyAction } from '../shortcuts.js'

export const study = () => ({
  // loading -> setup -> study -> summary -> setup
  phase: 'loading',
  direction: 'f2b',

  // 学習設定
  allCards: [],
  limit: '',

  // 学習中
  cards: [],
  step: 0,
  marked: {},

  async init() {
    this.allCards = await loadCards()
    this.phase = 'setup'
  },

  // ---- 学習設定 ----
  get studyCount() {
    return countToStudy(this.allCards.length, this.limit)
  },
  
  start() {
    this.cards = pickCards(this.allCards, this.limit)
    if (this.cards.length === 0) return
    this.step = 0
    this.marked = {}
    this.phase = 'study'
  },

  // ---- 学習 -----

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
  get status() {
    return this.card[`${this.direction}_status`]
  },

  forward() {
    if (this.isLastStep) {
      this.phase = 'summary'
      return
    }
    this.goTo(this.step + 1)
  },
  back() {
    if (this.isFirstStep) return
    this.goTo(this.step - 1)
  },
  goTo(step) {
    this.step = step
    this.$refs.card.scrollTop = 0
  },

  // 同じフラグをもう一度押すと未設定に戻す
  toggleStatus(status) {
    const next = this.status === status ? 'none' : status
    setStatus(this.card, this.direction, next)
    this.marked[this.card.uuid] = next
  },

  onKeyDown(event) {
    if (this.phase !== 'study') return
    const action = studyKeyAction(event)
    if (!action) return
    event.preventDefault()
    action(this)
  },

  quit() {
    this.phase = 'setup'
  },

  // ---- まとめ ----

  markedCount(status) {
    return Object.values(this.marked).filter((s) => s === status).length
  },
  restart() {
    this.phase = 'setup'
  }
})
