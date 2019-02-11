<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">قائمة الصلاحيات</h3>

      <div class="page-actions">
        <a href="#" class="btn btn-primary" @click.prevent="">
          <i class="icon-fa icon-fa-plus"/> اضافة صلاحية
        </a>
        <!-- <button class="btn btn-danger" @click.prevent="">
          <i class="icon-fa icon-fa-trash"/> Delete
        </button> -->
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h6> الصلاحيات</h6>
            <div class="card-actions" />
          </div>
          <div class="card-body">
            <table-component
              :data="getlist"
              sort-by="id"
              sort-order="desc"
              table-class="table"
              ref="table"
            >
              
              <table-column show="role" label="الاسم"/>
              
              <table-column
                show="created_at"
                label="تاريخ الاضافة"
                data-type="date:YYYY-MM-DD h:i:s"
              />
              <table-column
                :sortable="false"
                :filterable="false"
                label=""
              >
                <template slot-scope="row">
                  <div class="table__actions">
                    <!-- <router-link to="/admin/users/profile"> -->
                      <a class="btn btn-default btn-sm" @click="">
                        <i class="icon-fa icon-fa-search"/> تعديل
                      </a>
                    <!-- </router-link> -->
                    <a
                      class="btn btn-default btn-sm"
                      data-delete
                      data-confirmation="notie"
                      @click="deletePermission(row.id)"
                    >
                      <i class="icon-fa icon-fa-trash"/> حذف
                    </a>
                  </div>
                </template>

              </table-column>

            </table-component>

          </div>
        </div>
      </div>
    </div>
    
  </div>
</template>
<script>
import { TableComponent, TableColumn } from 'vue-table-component'

export default {
  components: {
    TableComponent,
    TableColumn
  },
  data () {
    return {
    	permissionList:[],
    }
  },
  methods:{
  	async getlist ({ page, filter, sort }) {
      try {
        const response = await axios.get(`/api/admin/permissions/get`)

        var allData = response.data;
        var beforeFilter = response.data.data;       

        var filterData =  beforeFilter.filter(function(key) {
        //console.log(key);                      
          return key.role.toLowerCase().indexOf(filter.toLowerCase()) > -1;
        });
        
        filterData.sort((a,b) => (sort.order == "asc" ) ? 1 : ((sort.order == "desc" ) ? -1 : 0)); 
        // console.log(filterData);
        // sort.fieldName & sort.order
        return {
          data: filterData,          
          pagination: {
            totalPages: allData.last_page,
            currentPage: page,
            count: allData.count
          }
        }
      } catch (error) {
        if (error) {
          window.toastr['error']('حذث خطأ اثناء لعرض ', 'Error')
          this.$Progress.fail();
        }
      }
    },
    deletePermission (id) {
      let self = this
      window.notie.confirm({
        text: 'هل انت متأكد ؟',
        cancelCallback: function () {
          window.toastr['info']('الغاء')
        },
        submitCallback: function () {
          self.deleteData(id)
        }
      })
    },
    async deleteData (id) {

    	if(id == 1){
    		window.toastr['error']('لا يمكن حذف هذه الصلاحيه ! ..... يمكنك تعديل الاسم فقط', 'Error')
	        this.$Progress.fail();
    	}else{
	      try {
	        let response = await window.axios.delete('/api/admin/permissions/' + id)
	        this.$refs.table.refresh();
	        window.toastr['success']('تم الحذف', 'Success')
	      } catch (error) {
	        if (error) {
	          window.toastr['error']('حدث خطأ أثناء الحذف', 'Error')
	          this.$Progress.fail();
	        }
	      }
    	}
    },
  	
  	

  },
  created(){  	
  	//this.permissionList =  axios.get(`/api/admin/permissions/get`);
  	//this.adminRoutes = this.$router.options.routes[2].children;
    //console.log(this.permissionList);

  },
}

</script>