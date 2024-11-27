import { reactive } from 'vue'

export const notificationsStore = reactive({
  count: 0,
  latest_notifications: {
    data: [],
    links: {}
  },
  increment() {
    this.count++
  },
  updateCount(newState) {
    this.count = newState;
  },
  setNotifications(notifications) {
    const plainData = JSON.parse(JSON.stringify(notifications.data));
    this.latest_notifications = {
      ...notifications,
      data: plainData,
    };
    // this.updateCount(plainData.length);
  },
  addNotification(notification) {
    const newNotif = {
      data: {
        ...notification,
        type: "comment"
      },
      id: notification.id || Date.now(),
    };
    this.latest_notifications.data.unshift(newNotif);
    this.increment();
  },
  clearNotifications() {
    this.latest_notifications = { data: [], links: {} };
    this.updateCount(0);
  },
})