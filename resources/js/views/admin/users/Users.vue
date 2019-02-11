<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">قائمة الاعضاء</h3>

      

      <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Users</a></li>
        <li class="breadcrumb-item active">Users</li>
      </ol> -->
      <div class="page-actions">
        <a href="#" class="btn btn-primary" @click.prevent="newModal">
          <i class="icon-fa icon-fa-plus"/> اضافة عضو
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
            <h6> الاعضاء</h6>
            <div class="card-actions" />
          </div>
          <div class="card-body">
            <table-component
              :data="getUsers"
              sort-by="id"
              sort-order="desc"
              table-class="table"
              ref="table"
            >
              <table-column label="الصوره">
                 <template slot-scope="row">                        
                          <img :src="getProfilePhoto(row.avatar)" alt="Avatar" 
                          style="width: 36px;border-radius: 2px;">
                 </template>
              </table-column>
              <table-column show="name" label="الاسم"/>
              <table-column show="email" label="البريد"/>
              <table-column show="phone" label="الهاتف"/>
              <table-column show="role" label="الصلاحية"/>
              <table-column show="arrears" label="المديونية"/>
              <table-column show="active" label="الحاله"/>
              
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
                      <a class="btn btn-default btn-sm" @click="editModal(row)">
                        <i class="icon-fa icon-fa-search"/> تعديل
                      </a>
                    <!-- </router-link> -->
                    <a
                      class="btn btn-default btn-sm"
                      data-delete
                      data-confirmation="notie"
                      @click="deleteUser(row.id)"
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
    

    <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateUser() : createUser()">
                    <div class="modal-body">
                         <div class="form-group">
                            <input v-model="form.name" type="text" name="name"
                                placeholder="Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                         <div class="form-group">
                            <input v-model="form.email" type="email" name="email"
                                placeholder="Email Address"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>

                         


                        <div class="form-group">
                            <select name="role" v-model="form.role" id="role" class="form-control" :class="{ 'is-invalid': form.errors.has('role') }">
                                <option value="">Select User Role</option>
                                <option value="admin">Admin</option>
                                <option value="user"> User</option>
                            </select>
                            <has-error :form="form" field="role"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.password" type="password" name="password" id="password" placeholder="Password"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                            <has-error :form="form" field="password"></has-error>
                        </div>

                        <!-- <div class="form-group">
                            <input type="file" name="avatar"
                            @change="updateProfile" 
                            id="avatar" placeholder="avatar"
                            class="form-control" 
                            :class="{ 'is-invalid': form.errors.has('avatar') }"
                            >
                            <has-error :form="form" field="avatar"></has-error>
                        </div> -->

                        <div class="form-group">
                                    <label for="avatar" class="col-sm-2 control-label"> Photo</label>
                                    <div class="col-sm-6">
                                        <input type="file" @change="updateProfile" name="avatar" class="form-input">
                                    </div>

                                </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>

                </form>

                </div>
            </div>
        </div>


  </div>
</template>

<script type="text/babel">
import { TableComponent, TableColumn } from 'vue-table-component'

export default {
  components: {
    TableComponent,
    TableColumn
  },
  data () {
    return {
      editmode: false,
      //users: [],
      form: new Form({
          id:'',
          name : '',
          email: '',
          password: '',
          role: '',
          avatar: '',
      })
    }
  },
  methods: {
    async getUsers ({ page, filter, sort }) {
      try {
        const response = await axios.get(`/api/admin/users/get?page=${page}`)

        var allData = response.data;
        var beforeFilter = response.data.data;       

        var filterData =  beforeFilter.filter(function(key) {
        //console.log(key);                      
          return key.name.toLowerCase().indexOf(filter.toLowerCase()) > -1 || key.email.toLowerCase().indexOf(filter.toLowerCase()) > -1;
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
          window.toastr['error']('حذث خطأ اثناء الاضافة ', 'Error')
          this.$Progress.fail();
        }
      }
    },
    deleteUser (id) {
      let self = this
      window.notie.confirm({
        text: 'هل انت متأكد ؟',
        cancelCallback: function () {
          window.toastr['info']('الغاء')
        },
        submitCallback: function () {
          self.deleteUserData(id)
        }
      })
    },
    async deleteUserData (id) {
      try {
        let response = await window.axios.delete('/api/admin/users/' + id)
        this.users = response.data
        this.$refs.table.refresh();
        window.toastr['success']('تم الحذف', 'Success')
      } catch (error) {
        if (error) {
          window.toastr['error']('حدث خطأ أثناء الحذف', 'Error')
          this.$Progress.fail();
        }
      }
    }, /************* new work ****************/
    getProfilePhoto(avatar){
        return "/dashboard/uploads/users/" + avatar ;
    },
    updateProfile(e){
        let file = e.target.files[0];
        let reader = new FileReader();

        let limit = 1024 * 1024 * 2;
        if(file['size'] > limit){
            window.toastr['error']('الصورة المختارة حجمها كبير', 'Error')
            return false;
        }
        reader.onloadend = (file) => {
            this.form.avatar = reader.result;
        }
        reader.readAsDataURL(file);
    },    
    updateUser(){
        this.$Progress.start();
        this.form.post('/api/admin/users/update/'+this.form.id)
        .then(() => {
            // success
            $('#addNew').modal('hide');
            
            window.toastr['success']('تم تحديث البيانات بنجاح', 'Success')

            this.$Progress.finish();
            this.$refs.table.refresh();
            //Fire.$emit('reload');
            //window.location.reload()
        })
        .catch(() => {
            this.$Progress.fail();
        });

    },
    editModal(user){
        this.editmode = true;
        this.form.reset();
        $('#addNew').modal('show');
        this.form.fill(user);
    },
    newModal(){
        this.editmode = false;
        this.form.reset();
        $('#addNew').modal('show');
    },
    
    createUser(){ 
    console.log(this.form);     

        this.$Progress.start();

        this.form.post('/api/admin/users/post')
        .then(()=>{

            $('#addNew').modal('hide')
            this.$refs.table.refresh();
            window.toastr['success']('تمت الاضافة بنجاح', 'Success')
            this.$Progress.finish();           

        })
        .catch(()=>{
          
            window.toastr['error']('حدث خطأ أثناء الاضافة', 'Error')
            this.$Progress.fail();
          
        })
    },
    

  },
  created(){
  },
}
</script>
