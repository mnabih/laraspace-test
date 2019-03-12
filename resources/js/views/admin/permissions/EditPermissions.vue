<template>
    <div class="main-content">
        hi from edit permissions vue
        <br>
        {{id}}
    </div>

</template>    
<script type="text/babel">
export default {
    data () {
        return {
            id: 0,
            RolepermissionList:[],
            allAdminMainRoutes:[],
            childRoutes:[]            
        }
    },
    methods:{
        
    },
    created() {
        this.id = this.$route.params.id;
        axios.get(`/api/admin/permissions/edit-permissions/` + this.id).then(response => this.RolepermissionList = response.data);
        this.allAdminMainRoutes = this.$router.options.routes[3].children;
        
    },
}
</script>
<style>
</style>