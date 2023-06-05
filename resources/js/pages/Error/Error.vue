<template>
  <div class="error-page">
    <b-container>
      <div class="errorContainer mx-auto">
        <h1 class="errorCode">404</h1>
        <p class="errorInfo">
            Opps, it seems that this page does not exist.
        </p>
        <p class="errorHelp mb-3">
            If you are sure it should, search for it.
        </p>
        <b-form>


        </b-form>
      </div>

    </b-container>
  </div>
</template>

<script>
export default {
  name: 'ErrorPage',
};
</script>

<style src="./Error.scss" lang="scss" scoped />
