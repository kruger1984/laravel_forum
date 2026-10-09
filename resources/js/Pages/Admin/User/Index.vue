<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Users</h3>

        </div>
        <div>
            <div class="border border-gray-200 rounded-lg">
                <table class="text-center w-full text-md">
                    <thead class="w-full bg-grey-100">
                    <tr>
                        <th class="p-4 text-gray-700">ID</th>
                        <th class="p-4 text-gray-700">Title</th>
                        <th class="p-4 text-gray-700">Roles</th>
                        <th class="p-4 text-gray-700">Change Role</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="users in users" class="text-gray-500">
                        <td class="p-4">{{ users.id }}</td>
                        <td class="p-4">{{ users.name }}</td>
                        <td class="p-4">
                            <div>
                                <p v-for="role in users.role">
                                    {{ role.code }}
                                </p>
                            </div>
                        </td>
                        <td class="p-4">
                            <a href="#">Choose</a>
                            <div class="text-left">
                                <div v-for="role in roles" class="mb-2">
                                    <input class="mr-2" type="checkbox" :id="role.id" :value="role.id">
                                    <label class="cursor-pointer" :for="role.id">{{ role.code}}</label>
                                </div>
                            </div>
                        </td>

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
        'users',
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
