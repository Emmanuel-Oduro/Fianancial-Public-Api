export default function (Vue) {
    Vue.auth = {

        // this we will set our tokon inside our localstorage to authorized the login user
        setToken  (token) {
            localStorage.setItem('token', token)
            // localStorage.setItem('expiration', expiration)
        },

        // this we will get our token from the localstorage of our local machine.
        getToken  () {
            var token = localStorage.getItem('token')

            if (! token) {
                return null;

                // if (Date.now() > parseInt(expiration) ) {
                //     this.destroyToken()
                //     return null
                // }else{
                //     return token
                // }
            }else
                return token;
        }, 

        // this will destroy the token and log the user out from the application
        destroyToken  () {
            localStorage.removeItem('token')
        },


        // this will check if the user is authenticated or not if yes grant access else unAuthorized
        isAuthenticated  () {
            if (this.getToken()) {
                return true;
            } else {
                return false;
            }
        }
    }

        // this we define our new User function to access from the app.js file as global
    Object.defineProperties(Vue.prototype, {
        $auth: {
            get () {
                return Vue.auth
            }
        }
    })
}