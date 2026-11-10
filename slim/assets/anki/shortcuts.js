const STUDY_KEYS = {
  Space: (s) => s.forward(),
  Enter: (s) => s.forward(),
  NumpadEnter: (s) => s.forward(),
  ArrowRight: (s) => s.forward(),

  ArrowLeft: (s) => s.back(),

  KeyK: (s) => s.back(), // vim k
  KeyJ: (s) => s.forward(), // vim j
  KeyH: (s) => s.back(), // vim h
  KeyL: (s) => s.forward(), // vim l

  KeyM: (s) => s.toggleStatus('memorized'),
  KeyD: (s) => s.toggleStatus('memorized'), // done
  KeyW: (s) => s.toggleStatus('weak'),
}

const isTyping = (el) =>
  el instanceof HTMLElement &&
  (el.isContentEditable || ['INPUT', 'TEXTAREA', 'SELECT'].includes(el.tagName))

export const studyKeyAction = (event) => {
  if (event.ctrlKey || event.metaKey || event.altKey) return null
  if (event.isComposing || isTyping(event.target)) return null
  if (event.repeat) return null
  return STUDY_KEYS[event.code] ?? null
}
