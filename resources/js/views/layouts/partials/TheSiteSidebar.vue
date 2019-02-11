<template>
  <div class="sidebar-left">
    <div class="sidebar-body scroll-pane">
      <div class="side-nav">
        <v-collapse accordion>

          <ul style="list-style: none;">
            <li v-for="route in adminRoutes">

              <v-collapse-item >
                <router-link :to="route.name" slot="item-title">
                  <i :class="route.icon"> </i>
                  {{route.title}}
                </router-link>
              </v-collapse-item>

            </li>
          </ul>

          <ul style="list-style: none;">
            <li v-for="route in adminRoutesPsermissions">

              <v-collapse-item >
                <router-link :to="route.name" slot="item-title">
                  <i :class="route.icon"> </i>
                  {{route.title}}
                </router-link>
              </v-collapse-item>

            </li>
          </ul>         

        </v-collapse>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {
    
  },
  data () {
    return {
      adminRoutes:[],
      adminRoutesPsermissions:[],
    }
  },
  methods:{
    
  },
  created(){
    this.adminRoutes = this.$router.options.routes[2].children;
    this.adminRoutesPsermissions = this.$router.options.routes[3].children;
    //console.log(this.adminRoutes);
    //console.log(this.adminRoutesPsermissions);

  }
}
</script>
