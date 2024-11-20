// store.js
import { reactive } from 'vue'

export const notificationsStore = reactive({
  count: 0,
  increment() {
    this.count++
  },
  update(newState){
    this.count = newState;
  }
})