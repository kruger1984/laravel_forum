<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Roles</h3>
            <Link :href="route('admin.roles.create')" class="block w-1/6 px-2 py-1 mr-4 bg-white border border-gray-300 rounded-lg text-center">+ Role</Link>

        </div>
        <div>
            <div class="border border-gray-200 rounded-lg">
                <table class="text-center w-full text-md">
                    <thead class="w-full bg-grey-100">
                    <tr>
                        <th class="p-4 text-gray-700">ID</th>
                        <th class="p-4 text-gray-700">Title</th>
                        <th class="p-4 text-gray-700">Code</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="role in roles" class="text-gray-500">
                        <td class="p-4">{{role.id }}</td>
                        <td class="p-4">{{role.title }}</td>
                        <td class="p-4">{{role.code}}</td>

                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    name: "Index",
    props: [
        'roles'
    ],
    layout: AdminLayout,
    components: {
        Link
    },

}
</script>

<style scoped>

</style>
