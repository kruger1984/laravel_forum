<template>
    <div>
        <div class="bg-white border-b border-gray-300 py-4">
            <div class="w-3/4 mx-auto">
                <Link :href="route('sections.index')" class="mr-4">Forum</Link>
                <Link :href="route('users.personal')" class="mr-4">Personal account</Link>
                <Link :href="route('admin.main.index')">Admin Page</Link>
            </div>
        </div>
        <div class="w-3/4 flex items-start mx-auto py-4">
            <div class="w-1/4 bg-white mr-4 border border-gray-300">
                <h3 class="text-xl mr-4 p-4">Menu</h3>
                <div>
                    <div>
                        <Link class="block w-full p-4 border-b border-t border-gray-300" :href="route('admin.main.index')">Statistics</Link>
                    </div>
                    <div>
                        <Link class="block w-full p-4 border-b border-gray-300" :href="route('admin.complaints.index')">Complaints</Link>
                    </div>
                    <div>
                        <Link class="block w-full p-4 border-b border-gray-300" :href="route('admin.roles.index')">Roles</Link>
                    </div>
                    <div>
                        <Link class="block w-full p-4 border-b border-gray-300" :href="route('admin.users.index')">Users</Link>
                    </div>
                </div>
            </div>
            <div class="w-3/4 bg-white p-4 border border-gray-300">

                <slot/>
            </div>
        </div>

    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "AdminLayout",
    components: {
        Link
    }
}
</script>

<style scoped>

</style>
