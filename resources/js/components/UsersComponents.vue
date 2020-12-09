<template>
<div>
    <!-- Just an image -->
<nav class="navbar navbar-light bg-info mt-3">
  <a class="navbar-brand" href="#">
    <img :src="getlaravelLogo()" width="40" height="40" alt="" loading="lazy">
    <img :src="getapiLogo()" width="40" height="40" alt="" loading="lazy">
    <img :src="getvueLogo()" width="40" height="40" alt="" loading="lazy" class="pull-right" style="float:right">
  </a>
</nav>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
      </li>
      <li class="nav-item">
      </li>
      <li class="nav-item">
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button type="button" class="btn btn-outline-success my-2 my-sm-0" >Search</button>
    </form>
  </div>
</nav>

<h2 style="font-family:times new romans; font-weight:bold">Add New </h2>
    <form @submit.prevent="addStudent" class="mb-3">
        <div class="row">
      <div class="form-group">
          <div class="col-md-12">
        <input type="text" autofocus="autofocus" class="form-control" placeholder="First Name" v-model="user.name" autocomplete="off">
      </div>
      </div>
       <div class="form-group">
          <div class="col-md-12">
        <input type="text" class="form-control" placeholder="Last Name" v-model="user.email" autocomplete="off">
      </div>
      </div>
        <div class="form-group">
          <div class="col-md-12">
              <select name="" class="form-control" id="" v-model="user.role_name">
                  <option value="">-----</option>
                  <option value="admin">Admin</option>
                  <option value="customer">Customer</option>
              </select>
      </div>
      </div>
        <div class="form-group">
          <div class="col-md-12">
        <select name="" class="form-control" v-model="user.user_status">
                  <option value="">-----</option>
                  <option value="approve">Approve</option>
                  <option value="reject">Reject</option>
              </select>
      </div>
      </div>

      </div>
          <div class="form-row">
    <div class="form-group col-md-6">
      <button id="submit" type="submit" class="btn btn-outline-success btn-block">Save</button>
          </div>
          <div class="form-group col-md-6">
    <button type="button" @click="clearForm()" class="btn btn-outline-danger btn-block">Refresh</button>
          </div>
          </div>
    </form>

  <div class="card">
     <div class="card-body">
         <div class="card-header bg-primary" style="text-transform:uppercase;  color:#fff; font-size:25px; font-family:times new romans; text-align:center">LARAVEL VUE CRUD <b>API</b> APP </div>
      <table class="table table-bordered">
          <thead>
              <tr>
              <th>Student Name</th>
              <th>Gender</th>
              <th>Phone</th>
              <th>Class</th>
              <th>Country</th>
              <th class="text-right">Action</th>
              </tr>
          </thead>
          <tbody>
              <tr v-for="user in users" v-bind:key="user.id">
              <td>{{user.name}}</td>
              <td>{{user.email}}</td>
              <td>{{user.role_name}}</td>
              <td>{{user.user_status}}</td>
              <td>
                <a @click="editStudent(user)"  class="btn btn-info btn-xs" href="#" ><i class="fa fa-pencil "></i> </a>
                <a @click="deleteStudent(user.id)" class="btn btn-danger btn-xs" href="#"> <i class="fa fa-trash text-red"></i></a></td>

              </tr>
          </tbody>
      </table>
         <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchStudents(pagination.prev_page_url)">Previous</a></li>

        <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
    
        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchStudents(pagination.next_page_url)">Next</a></li>
      </ul>
    </nav>
      </div>
  </div>

</div>

</template>

<style>
.image{
    width: 30px !important;
}
td{
    font-family:Arial, Helvetica, sans-serif;
    font-size: 18px;
    text-align: center;
}
th{
    font-family:Arial, Helvetica, sans-serif;
    font-size: 18px;
    text-align: center;
}
</style>



<script>

export default {
  data() {
    return {
      users: [],
      user: {
      name: '',
      email: '',
      role_name: '',
      user_status: '',
      // class: '',
      // phone: '',
      // image: '',
      styleObject: {
                   width: '100px',
                   height: '100px'
                }
      },
      user_id: '',
      pagination: {},
      edit: false,
      get_avater: true
        
    };
  },

  created() {
    this.fetchAllUsers();
  },

  methods: {

        upload_avatar(e){
              let file = e.target.files[0];
                let reader = new FileReader();  

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                    //console.log('RESULT', reader.result)
                     this.student.image = reader.result;
                    }              
                     reader.readAsDataURL(file);
                }else{
                    alert('File size can not be bigger than 2 MB')
                }
            },
             //For getting Instant Uploaded Photo
            get_avatar(){
               let photo = (this.student.image) ? this.student.image : "images/students/"+ this.student.image;
               return photo;
            },

      getstudentProfile(){
          return "images/students/" + this.student.image;
      },

      getvueLogo(){
          return "images/vue.jpeg";
      },
       getlaravelLogo(){
          return "images/laravel.png";
      },

       getapiLogo(){
          return "images/api.png";
      },


    fetchAllUsers(page_url) {
      let paginate = this;
      page_url = page_url || '/api/user';
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.students = res.data;
          paginate.makePagination(res.meta, res.links);
        })
        .catch(err => console.log(err));
    },


    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };

      this.pagination = pagination;
    },
    showModal(student) {
          this.student.id = student.id;
          this.student.student_id = student.id;
            $('#studentDeleteModal').modal('show');
        },
    deleteStudent(id) {
         
      if (confirm('Are You Sure You Want to Delete?' )) {
        fetch(`api/v1/students/${id}`, {
          method: 'delete'
        })
          .then(res => res.json())
          .then(data => {
            alert('Student Removed Successfully!');
            this.fetchStudents();
          })
          .catch(err => console.log(err));
      }
    },



    // Add New Student

    addStudent() {
      if (this.edit === false) {
        // Add
        fetch('api/v1/students', {
          method: 'post',
          body: JSON.stringify(this.student),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert('Student Added Successfully');
            this.fetchStudents();
            // this.flashMessage.success({
            //     title: 'Success',
            //     message: 'Student Added Successfully!',
            //     time: 5000
            // });
            

          })
          .catch(err => console.log(err));
      } else {
        // Update
        fetch('api/v1/students', {
          method: 'put',
          body: JSON.stringify(this.student),
          headers: {
            'content-type': 'application/json'
          },
          
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert('Student Updated Successfully!');
            this.fetchStudents();
          })
          .catch(err => console.log(err));
      }
    },
    editStudent(student) {
      this.edit = true;
      this.student.id = student.id;
      this.student.student_id = student.id;
      this.student.first_name = student.first_name;
      this.student.last_name = student.last_name;
      this.student.gender = student.gender;
      this.student.phone = student.phone;
      this.student.country = student.country;
      this.student.class = student.class;
      this.student.image = student.image;
      $('#submit').text('Update');
     
    },
    showStudent(student) {
    //   this.edit = true;
      this.student.id = student.id;
      this.student.student_id = student.id;
      this.student.first_name = student.first_name;
      this.student.last_name = student.last_name;
      this.student.gender = student.gender;
      this.student.phone = student.phone;
      this.student.country = student.country;
      this.student.class = student.class;
      this.student.image = student.image;
    },
    clearForm() {
      this.edit = false;
      this.student.id = null;
      this.student.student_id = null;
      this.student.first_name = '';
      this.student.last_name = '';
      this.student.gender = '';
      this.student.phone = '';
      this.student.country = '';
      this.student.class = '';
      this.student.image = '';
      $('#submit').text('Save');
    }
  }
};

$(document).ready(function(){
   if (this.edit === false) {
        $("#get_avater").hide();
    }
    
})
</script>
