console.log('[anki] main.js', window.Alpine ? 'Alpineのあとに実行' : 'Alpineより先に実行')

document.addEventListener('alpine:init', () => {
  console.log('[anki] alpine:init')

  window.Alpine.data('study', () => ({
    message: 'Alpineのコンポーネントが動いています',
  }))
})
