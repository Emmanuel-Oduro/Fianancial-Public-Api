<template>
    <h1>Home Dashboard</h1>
</template>

<script>
export default {
    data () {
        return {
            products: []
        }
    },

    created () {
        axios.get('api/products')
    }
}
</script>