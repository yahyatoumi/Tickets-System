<template>
  <div id="dropdown"></div>
  <div class="md:flex md:flex-col md:h-screen">
    <div class="md:flex md:flex-col md:h-screen">
      <Header />
      <div class="md:flex md:grow md:overflow-hidden">
        <SideBarNav class="hidden shrink-0 p-12 w-56 bg-indigo-900 overflow-y-auto md:block" />
        <main class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto">

          <!-- This is where toast will be inserted -->
          <flash-messages />

          <!-- This is where page content will be inserted -->
          <slot></slot>

        </main>
      </div>

      <footer>
        <!-- footer content here -->
      </footer>
    </div>
  </div>
</template>

<script>
import SideBarNav from '@/Shared/SideBarNav.vue'
import Header from '@/Shared/Header.vue'
import FlashMessages from '@/Shared/FlashMessages.vue'




export default {
  components: {
    SideBarNav,
    Header,
    FlashMessages,
  },
  computed: {
    // notificationCount() {
    //   return this.$page.props.notifications.unread_count
    // }
  },
  mounted(){
    console.log(";o;o;o;", this.$page.props.notifications.unread_count)
    this.$notificationsStore.updateCount(this.$page.props.notifications.unread_count)
  }
}
</script>