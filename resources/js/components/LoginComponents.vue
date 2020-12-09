
<template>

    <div class="login-page">
      <transition name="fade">
         <div  class="wallpaper-login"></div>
      </transition>
      <div class="wallpaper-register"></div>

      <div class="container">
         <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-8 mx-auto">
               <div  class="card login" >
                  <h1>User Sign In</h1>
                  <form id="Login" @submit.prevent="Login" class="form-group">
                     <input v-model="user.email" v-bind:class="{'error-border': error.email}" type="email" class="form-control" placeholder="Email" required>
                     <input v-model="user.password" v-bind:class="{'error-border': error.password}" type="password" class="form-control" placeholder="Password" required>
                     <input type="submit" class="btn btn-primary" @click="Login">
                     <p>Don't have an account? <a href="#" >Sign up here</a>
                     </p>
                     <p><a href="#">Forgot your password?</a></p>
                  </form>
               </div>

               <!-- <div  class="card register" >
                  <h1>Sign Up</h1>
                  <form class="form-group">
                     <input v-model="user.email" type="email" class="form-control" placeholder="Email" required>
                     <input v-model="user.password" type="password" class="form-control" placeholder="Password" required>
                     <input v-model="user.confirm" type="password" class="form-control" placeholder="Confirm Password" required>
                     <input type="submit" class="btn btn-primary" @click="doRegister">
                     <p>Already have an account? <a href="#" >Sign in here</a>
                     </p>
                  </form>
               </div> -->
            </div>
         </div>

      </div>
      
   </div>

</template>

<style>
    p {
         line-height: 1rem;
    }
     .card {
         padding: 20px;
    }
     .form-group input {
         margin-bottom: 20px;
    }
     .login-page {
         align-items: center;
         display: flex;
         height: 100vh;
    }
     .login-page .wallpaper-login {
         background: url(https://images.pexels.com/photos/32237/pexels-photo.jpg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260) no-repeat center center;
         background-size: cover;
         height: 100%;
         position: absolute;
         width: 100%;
    }
     .login-page .fade-enter-active, .login-page .fade-leave-active {
         transition: opacity 0.5s;
    }
     .login-page .fade-enter, .login-page .fade-leave-to {
         opacity: 0;
    }
     .login-page .wallpaper-register {
         background: url(https://images.pexels.com/photos/533671/pexels-photo-533671.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260) no-repeat center center;
         background-size: cover;
         height: 100%;
         position: absolute;
         width: 100%;
         z-index: -1;
    }
     .login-page h1 {
         margin-bottom: 1.5rem;
    }
     .error {
         animation-name: errorShake;
         animation-duration: 0.3s;
    }
     @keyframes errorShake {
         0% {
             transform: translateX(-25px);
        }
         25% {
             transform: translateX(25px);
        }
         50% {
             transform: translateX(-25px);
        }
         75% {
             transform: translateX(25px);
        }
         100% {
             transform: translateX(0);
        }
    }
     
</style>

    <script>
export default {

    mounted(){
        console.log('Component mounted.')
    },

    data(){

        return {

            user: {

                client_id : 2,
                client_secret : 'rGLtL1VxxljUtGRYS9ONKImLHdHEhH0onwPo66Gy',
                grant_type : 'password',
                email: "admin@gmail.com",
                password: "admin",

            },

            error: '',
            msg: '',
            loading: false,
            success: false,
            showDismissibleAlert: false,
            pageLoding: '',
            btnLoading: false,
            settingActive: false,
   }
},
        methods: {

            Login() {

                this.loading = true;
                this.btnLoading = true;
                // this.$http.post('http://127.0.1:8000/oauth/token', this.user)
                axios.post('api/login', this.user)
                .then(response => {
                    // console.log(response.data);
                    // this.$auth.setToken(response.body.access_token, response.expire_in + Date.now())
                    this.$auth.setToken(response.data.token)
                    this.msg = response.data.msg;
                    this.showDismissibleAlert = false;
                    this.user.email = '';
                    this.user.password = '';
                    this.loading = false;
                    this.btnLoading = false;
                    this.$router.push('/home');
                })
            },
      
      doRegister() {
         if (this.emailReg === "" || this.passwordReg === "" || this.confirmReg === "") {
            this.emptyFields = true;
         } else {
            alert("You are now registered");
         }
      }
   }
};

    </script>