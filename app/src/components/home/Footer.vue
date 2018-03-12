<template>
    <v-bottom-nav
        shift
        :value="true"
        :active.sync="e"
        :class="{
            'expoville base': e === 0,
            'expoville lighten-1': e === 1
        }"
    >
        <v-btn
            dark
            flat
        >
            <span>My task list</span>
            <v-icon>people</v-icon>
        </v-btn>
    </v-bottom-nav>
</template>

<script>
    import Vue from 'vue'

    export default {
        props:{
            e: Number
        },
        data () {
            return {
            }
        }
    }
</script>

<style lang="stylus">
</style>