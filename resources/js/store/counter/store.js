// store.js
import { reactive } from 'vue'

export const counterStore = reactive({
  count: 0,
  increment() {
    this.count++
  }
})