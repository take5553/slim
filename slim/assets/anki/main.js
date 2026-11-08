import { study } from './view/study.js'

document.addEventListener('alpine:init', () => {

  window.Alpine.data('study', study)
})
