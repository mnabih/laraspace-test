<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">Users</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Users</a></li>
        <li class="breadcrumb-item active">Users</li>
      </ol>
      <div class="page-actions">
        <a href="#" class="btn btn-primary" @click.prevent="newModal">
          <i class="icon-fa icon-fa-plus"/> New User
        </a>
        <button class="btn btn-danger">
          <i class="icon-fa icon-fa-trash"/> Delete
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h6>All Users</h6>
            <div class="card-actions" />
          </div>
          <div class="card-body">
            <table-component
              :data="getUsers"
              sort-by="row.name"
              sort-order="desc"
              table-class="table"
            >
              <table-column show="name" label="Name"/>
              <table-column show="email" label="Email"/>
              <table-column show="role" label="Role"/>
              <table-column
                show="created_at"
                label="Registered On"
                data-type="date:YYYY-MM-DD h:i:s"
              />
              <table-column
                :sortable="false"
                :filterable="false"
                label=""
              >
                <template slot-scope="row">
                  <div class="table__actions">
                    <router-link to="/admin/users/profile">
                      <a class="btn btn-default btn-sm" @click="editModal(user)">
                        <i class="icon-fa icon-fa-search"/> Edit
                      </a>
                    </router-link>
                    <a
                      class="btn btn-default btn-sm"
                      data-delete
                      data-confirmation="notie"
                      @click="deleteUser(row.id)"
                    >
                      <i class="icon-fa icon-fa-trash"/> Delete
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
      users: [],
      form: new Form({
          id:'',
          name : '',
          email: '',
          password: '',
          role: '',
      })
    }
  },
  methods: {
    async getUsers ({ page, filter, sort }) {
      try {
        const response = await axios.get(`/api/admin/users/get?page=${page}`)

        return {
          data: response.data.data,
          pagination: {
            totalPages: response.data.last_page,
            currentPage: page,
            count: response.data.count
          }
        }
      } catch (error) {
        if (error) {
          window.toastr['error']('There was an error', 'Error')
        }
      }
    },
    deleteUser (id) {
      let self = this
      window.notie.confirm({
        text: 'Are you sure?',
        cancelCallback: function () {
          window.toastr['info']('Cancel')
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
        window.toastr['success']('User Deleted', 'Success')
      } catch (error) {
        if (error) {
          window.toastr['error']('There was an error', 'Error')
        }
      }
    }, /************* new work ****************/
    updateUser(){
        this.$Progress.start();
        // console.log('Editing data');
        this.form.put('api/user/'+this.form.id)
        .then(() => {
            // success
            $('#addNew').modal('hide');
             swal(
                'Updated!',
                'Information has been updated.',
                'success'
                )
                this.$Progress.finish();
                 Fire.$emit('AfterCreate');
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

        //this.$Progress.start();

        this.form.post('/api/admin/users/post')
        .then(()=>{
            //Fire.$emit('AfterCreate');
            $('#addNew').modal('hide')

            window.toastr['success']('User Created in successfully', 'Success')
            loadUsers()

            //this.$Progress.finish();

            

        })
        .catch(()=>{
          
            window.toastr['error']('There was an error', 'Error')
          
        })
    },
    loadUsers(){
        axios.get("/api/admin/users/get").then(({ data }) => (this.users = data));
        
    },

  }
}
</script>
