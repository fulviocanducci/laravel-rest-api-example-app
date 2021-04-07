<template>
  <b-container class="bv-example-row p-3">
    <b-form @submit="onSubmit" @reset="onReset" id="form1" name="form1">
      <b-form-group
        id="input-group-1"
        label="Email:"
        label-for="input-1"
        description=""
      >
        <b-form-input
          id="input-1"
          v-model="form.email"
          type="email"
          placeholder="Enter email"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Password:" label-for="input-2">
        <b-form-input
          type="password"
          id="input-2"
          v-model="form.password"
          placeholder="Enter password"
          required
        ></b-form-input>
      </b-form-group>      

      <b-button type="submit" variant="primary">Submit</b-button>
      <b-button type="reset" variant="danger">Reset</b-button>
    </b-form>
    
  </b-container>
</template>

<script>
export default {
    data() {
      return {
        form: {
          email: '',
          password: ''         
        }
      }
    },
    methods: {
      onSubmit(event) {
        event.preventDefault()
        axios.post('http://localhost:8000/api/v1/auth/login', this.form)
            .then(result => {
                if (result.status === 200) {
                    window.localStorage.setItem("@auth", result.data.access_token);
                    this.$router.push("/");
                }
            });
      },
      onReset(event) {
        event.preventDefault()
        this.form.email = '';
        this.form.password = '';
      }
    }
}
</script>

<style>
   #form1 {
    width: 100%;
    max-width: 410px;
    padding: 15px;
    margin: auto;
}
</style>