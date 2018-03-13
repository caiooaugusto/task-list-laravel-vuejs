<template>
    <v-toolbar light class="toolbar-tasklist">
        <div class="toolbar-title"> Task list Laravel, VueJS2, VuetifyJS including Cordova</div>
        <!--<logo class="logo-toolbar"></logo>-->
    </v-toolbar>
</template>

<script>
    import Logo from '../shared/Logo.vue'
    export default {
        components: {
            Logo: Logo
        },
        data () {
            return {
            }
        },   
        methods: {
        }
    }
</script>

<style lang="stylus" scoped>
    .toolbar-title
        font-size 20px
        font-weight 600

    .toolbar-tasklist
        z-index 1
        top 0px
        position fixed
        display flex
        justify-content center
        align-items center

    .logo-toolbar
        margin-top -8px
        width 300px !important
</style>