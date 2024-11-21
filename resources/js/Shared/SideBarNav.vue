<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
      <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center gap-2 py-3" href="/notifications">
      <div :class="isUrl('/notifications') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
        Notifications
      </div>
      <div class="group flex items-center cursor-pointer select-none relative">
        <div v-if="$notificationsStore.count > 0"
          class="w-4 h-4 absolute -right-1 -top-1 rounded-full flex bg-red-500 text-white justify-center items-center text-[10px] font-bold">
          {{ $notificationsStore.count }}
        </div>
        <v-icon name="md-notifications-sharp" class="text-gray-400 w-6 h-6" />
      </div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center gap-2 py-3" href="/tickets/fromyou">
      <div :class="isUrl('/tickets/fromyou') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">{{
        !isEndUser(auth) ? "From you" : "Tickets" }}</div>
      <v-icon name="io-ticket" class="fill-gray-400 group-hover:fill-white" />
      </Link>
    </div>
    <div v-if="!isEndUser(auth)" class="mb-4">
      <Link class="group flex items-center gap-2 py-3" href="/tickets/toyou">
      <div :class="isUrl('/tickets/toyou') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">To you</div>
      <v-icon name="io-ticket" class="fill-gray-400 group-hover:fill-white" />
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports">
      <div :class="isUrl('reports') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Reports</div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { isEndUser } from '@/helpers/rolesHelpers';



export default {
  components: {
    Link,
  },
  computed: {
    auth() {
      return this.$page.props.auth
    },
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
    isEndUser,
  },
  created() {
    window.Echo.channel(`channel_for_everyone.${this.auth.user.id}`)
      .listen(".ticket.created", (response) => {
        // messages.value.push(response.message);
        console.log("event catcheddd");
        this.$notificationsStore.increment()
      })
      .listen(".ticket.updated", (response) => {
        // messages.value.push(response.message);
        console.log("event catcheddd");
        this.$notificationsStore.increment()
      })
  }
}
</script>