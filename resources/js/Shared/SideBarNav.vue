<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
      <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center gap-2 py-3" href="/tickets/fromyou">
      <div :class="isUrl('/tickets/fromyou') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">From you</div>
      <v-icon name="io-ticket" class="fill-gray-400 group-hover:fill-white" />
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center gap-2 py-3" href="/tickets/toyou">
      <div :class="isUrl('/tickets/fromyou') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">To you</div>
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
//   import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Link,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>