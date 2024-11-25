<template>
    <div>

        <Head title="Notifications" />
        <h1 class="mb-8 text-3xl font-bold">Notifications</h1>
        <single-notification v-for="notification in notifications.data"
            :key="notification.id" :notification="notification" />
        <pagination class="mt-6" :links="notifications.links" />
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import SingleNotification from '@/Shared/SingleNotification.vue'
import { isAdmin } from '@/helpers/rolesHelpers';


export default {
    components: {
        Head,
        Link,
        Pagination,
        SingleNotification
    },
    layout: Layout,
    props: {
        latest_notifications: Object,
        auth: Object
    },
    computed: {
        notifications() {
            return this.$notificationsStore.latest_notifications;
        },
    },
    methods: {
        navigateToEdit(userId) {
            if (isAdmin(this.auth))
                this.$inertia.visit(`/users/${userId}/edit`);
        },
        isAdmin,
    },
    mounted() {
        console.log("notificationsss", this.latest_notifications)
        this.$notificationsStore.updateCount(0)
        this.$notificationsStore.setNotifications(this.latest_notifications)
    }
}
</script>