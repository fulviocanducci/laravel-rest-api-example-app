<template>
    <div>
        <h1>Tasks</h1>
        <div class="text-center" v-if="items.length===0">
            <b-spinner label="Spinning"></b-spinner>
        </div>
        <b-table striped hover :items="items"></b-table>
    </div>
</template>

<script>
export default {
    data() {
        return  {
            items: []
        }
    }, 
    mounted() {
        axios.get('http://localhost:8000/api/v1/task')
            .then(result => {
                if (result.status === 200) {
                    this.items = result.data;
                }
            })
    }
}
</script>

<style>

</style>