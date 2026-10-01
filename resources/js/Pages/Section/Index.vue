<template>
    <div>
        <div class="flex items-center">
            <h3 class="text-xl mr-4">Sections</h3>
            <Link :href="route('sections.create')" class="block w-1/6 px-2 py-1 mr-4 bg-white border border-gray-300 rounded-lg text-center">+ Section</Link>
            <Link :href="route('branches.create')" class="block w-1/6 px-2 py-1 bg-white border border-gray-300 rounded-lg text-center">+ Branch</Link>
        </div>
    </div>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";
export default {
    name: "Index",
    layout: MainLayout,
    components: {
        Link
    }
}
</script>

<style scoped>

</style>
