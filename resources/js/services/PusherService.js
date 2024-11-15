import Pusher from 'pusher-js'
import { ref } from 'vue'

class PusherService {
    constructor() {
        this.pusher = null
        this.channels = new Map()
        this.notifications = ref([])
        this.unreadCount = ref(0)
    }

    init() {
        if (this.pusher) return

        this.pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            encrypted: true,
            // channelAuthorization: {
            //     endpoint: 'http://127.0.0.1:8000/broadcasting/auth',
            // }
        })
    }

    subscribe(channelName, events = {}) {
        if (!this.channels.has(channelName)) {
            const channel = this.pusher.subscribe(channelName)
            this.channels.set(channelName, channel)

            // Bind events to this channel
            Object.entries(events).forEach(([event, callback]) => {
                channel.bind(event, callback)
            })
        }
        
        return this.channels.get(channelName)
    }

    unsubscribe(channelName) {
        if (this.channels.has(channelName)) {
            this.pusher.unsubscribe(channelName)
            this.channels.delete(channelName)
        }
    }
}

export const pusherService = new PusherService()