<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Complaints</h3>
        </div>
        <div>
            <div class="border border-gray-200 rounded-lg">
                <table class="text-center text-md">
                    <thead class="w-full bg-grey-100">
                    <tr>
                        <th class="p-4 text-gray-700">ID</th>
                        <th class="p-4 text-gray-700">Text</th>
                        <th class="p-4 text-gray-700">User</th>
                        <th class="p-4 text-gray-700">Message Url</th>
                        <th class="p-4 text-gray-700">Status</th>
                        <th class="p-4 text-gray-700">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="complaint in complaints" class="text-gray-500">
                            <td class="p-4">{{complaint.id }}</td>
                            <td class="p-4">{{complaint.body }}</td>
                            <td class="p-4">{{complaint.user.name}}</td>
                            <td class="p-4">
                                <Link :href="route('themes.show', complaint.theme_id) + `#${complaint.message_id}`"> Url</Link>
                            </td>
                            <td class="p-4 whitespace-nowrap">{{complaint.is_solved ? 'Solved' : 'in progress' }}</td>
                            <td class="p-4">
                                <a @click.prevent="update(complaint)" href="#" class="block w-6 h-6 mx-auto">
                                    <svg :class="[complaint.is_solved ? 'stroke-green-700' : 'stroke-red-700', 'w-6 h-6']" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </a>
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
        'complaints'
    ],
    layout: AdminLayout,
    components: {
        Link
    }, methods: {
        update(complaint) {
            axios.patch(`/admin/complaints/${complaint.id}`)
                .then((res) => {
                    complaint.is_solved = res.data.is_solved;
                })
        }
    }
}
</script>

<style scoped>

</style>
