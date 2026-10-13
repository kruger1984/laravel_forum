<template>
    <div>
        <div class="bg-white border-b border-gray-300 py-4">
            <div class="w-3/4 mx-auto flex items-center justify-between">
                <div>
                    <Link :href="route('sections.index')" class="mr-4">Forum</Link>
                    <Link :href="route('users.personal')" class="mr-4">Personal account</Link>
                    <Link :href="route('admin.main.index')">Admin Page</Link>
                </div>
                <div class="w-1/4 text-right">
                    <div class="relative">
                        <a @click.prevent="openNotification" href="#">
                            <span  class="mr-2">Notification</span>
                            <span>{{  this.$page.props.auth.notification_count }}</span>
                        </a>
                        <div v-if="this.$page.props.auth.notifications.length && isOpen"
                             class="absolute w-full">
                            <div v-for="notification in this.$page.props.auth.notifications"
                            class="p-4 border border-b border-gray-300 bg-white text-left"
                            >
                                <p>{{ notification.title }}</p>
                                <Link class="text-sky-600" :href="notification.url">Goto</Link>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="w-3/4 mx-auto py-4">
            <slot/>
        </div>

    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "MainLayout",

    created() {
        window.Echo.private(`users.notifications.${this.$page.props.auth.user.id}`)
            .listen('.user_notifications', res => {

                this.$page.props.auth.notifications.push(res.data)
                this.$page.props.auth.notification_count++
        })
    },
    components: {
        Link
    },
    data()  {
        return {
            isOpen: false,
        }
    },
    methods: {
        openNotification() {
            this.isOpen = !this.isOpen
            let ids = this.$page.props.auth.notifications.map(notification => notification.id)

            axios.patch('/notifications/update_collection', {ids: ids}).then(res => {
                this.$page.props.auth.notification_count = res.data.count
            })
        }
    }
}
</script>

<style scoped>

</style>
