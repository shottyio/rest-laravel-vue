<template>
  <main class="py-4">
    <div class="container">
      <div class="row justify-content-center">
        <form @submit.prevent="submit">
          <div class="form-group">
            <input
              type="text"
              placeholder="Login"
              id="login"
              class="form-control"
              :class="{ 'input-error': $v.login.$error && !$v.login.required }"
              v-model.trim="$v.login.$model"
            />
          </div>
          <div class="form-group">
            <input
              type="text"
              placeholder="Email"
              class="form-control"
              :class="{ 'input-error' : $v.email.$error && !$v.email.required || !$v.email.email || errors['email'] }"
              v-model.trim="email"
              @change="clearErrors"
            />
            <span class="error-message" v-if="!$v.email.email">Email entered incorrectly</span>
            <span class="error-message" v-if="errors['email']">{{ errors['email'][0] }}</span>
          </div>
          <div class="form-group">
            <input
              type="password"
              class="form-control"
              :class="{ 'input-error' : $v.password.$error && !$v.password.required || !$v.password.minLength}"
              v-model.trim="password"
              placeholder="Password"
            />
            <span
              v-if="!$v.password.minLength"
              class="error-message"
            >Password must be at least 6 characters</span>
          </div>
          <button class="btn btn-primary btn-block">Send</button>
        </form>
      </div>
    </div>
  </main>
</template>

<script>
// @ is an alias to /src
// import HelloWorld from '@/components/HelloWorld.vue'
import axios from "axios";
import { required, email, minLength } from "vuelidate/lib/validators";
export default {
  data: function() {
    return {
      login: "",
      email: "",
      password: "",
      errors: []
    };
  },
  validations: {
    login: {
      required
    },
    email: {
      required,
      email
    },
    password: {
      required,
      minLength: minLength(8)
    }
  },
  /*   mounted() {}, */
  methods: {
    clearErrors: function() {
      this.errors = [];
    },
    submit: function() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        axios.post("http://api.laravel/api/users", {
            login: this.login,
            email: this.email,
            password: this.password
          }).then(function() {
            console.log("мы зареганы!");
          }).catch(error => (this.errors = error.response.data.errors));
      }
    }
  }
};
</script>

<style lang="scss">
.input-error {
  border-color: #f79483;
}
.error-message {
  color: red;
}
</style>
