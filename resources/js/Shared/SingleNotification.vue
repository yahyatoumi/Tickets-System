<template>
    <div class="bg-white rounded-md shadow overflow-x-auto mb-3">
        <div class="w-full p-3 bg-white rounded shadow flex flex-shrink-0">
            <!-- Icon changes based on type -->
            <div tabindex="0" aria-label="group icon" role="img"
                class="focus:outline-none w-8 h-8 border rounded-full border-gray-200 flex flex-shrink-0 items-center justify-center">
                <v-icon :name="iconForType('ticket')" :class="iconClassForType(type)" />
            </div>
            <div class="pl-3 w-full">
                <div class="flex items-center justify-between w-full">
                    <p tabindex="0" class="focus:outline-none text-sm leading-none">
                        <span class="text-indigo-700">{{ actor }}</span>
                        <span v-if="type === 'created'"> created a new ticket: </span>
                        <span v-if="type === 'updated'"> updated the ticket: </span>
                        <Link 
                        :href="`/tickets/${ticket.id}/edit`"
                        class="text-indigo-700">{{ ticket.title }}</Link>
                    </p>
                    <div tabindex="0" aria-label="close icon" role="button" class="focus:outline-none cursor-pointer">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.5 3.5L3.5 10.5" stroke="#4B5563" stroke-width="1.25" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3.5 3.5L10.5 10.5" stroke="#4B5563" stroke-width="1.25" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <p tabindex="0" class="focus:outline-none text-xs leading-3 pt-1 text-gray-500">2 hours ago</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        notification: Object,
    },
    computed: {
        auth(){
            return this.$page.props.auth
        },
        ticket() {
            return this.notification.data.ticket
        },
        type() {
            return this.notification.data.type
        },
        actor(){
            let actor = null;
            if (this.type === "created"){
                actor = this.ticket.submitter
            }
            else if (this.type === "updated"){
                actor = this.notification.data.updater
            }

            if (actor?.id === this.auth.user.id){
                return "You"
            }
            return actor?.username
        }
    },
    mounted() {
        console.log("dataaaa2", this.type, this.notification.data?.updater?.username)
        console.log("dataaaa", this.notification.data?.updater, this.auth)
    },
    methods: {
        iconForType(type) {
            switch (type) {
                case 'ticket':
                    return 'io-ticket';
                case 'updated':
                    return 'io-refresh';
                default:
                    return 'io-alert';
            }
        },
        iconClassForType(type) {
            switch (type) {
                case 'created':
                    return 'fill-[#047857]';
                case 'updated':
                    return 'fill-[#1E3A8A]';
                default:
                    return 'fill-[#DC2626]';
            }
        },
    },
}

</script>