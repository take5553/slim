import { study } from './views/study.js'

document.addEventListener('alpine:init', () => {

  window.Alpine.data('study', study)
})
