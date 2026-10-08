<template>
    <div>
        <div class="bg-white border-b border-gray-300 py-4">
            <div class="w-3/4 mx-auto">
                <Link :href="route('sections.index')" class="mr-4">Forum</Link>
                <Link :href="route('users.personal')" class="mr-4">Personal account</Link>
                <Link :href="route('admin.main.index')">Admin Page</Link>
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
    components: {
        Link
    }
}
</script>

<style scoped>

</style>
