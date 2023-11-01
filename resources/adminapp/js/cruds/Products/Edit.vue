<template>
  <div class="container-fluid">
    <form @submit.prevent="submitForm">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-primary card-header-icon">
              <div class="card-icon">
                <i class="material-icons">edit</i>
              </div>
              <h4 class="card-title">
                {{ $t('global.edit') }}
                <strong>{{ $t('cruds.product.title_singular') }}</strong>
              </h4>
            </div>
            <div class="card-body">
              <back-button></back-button>
            </div>
            <div class="card-body">
              <bootstrap-alert />
              <div class="row">
                <div class="col-md-12">
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.name,
                      'is-focused': activeField == 'name'
                    }"
                  >
                    <label class="bmd-label-floating">{{
                      $t('cruds.product.fields.name')
                    }}</label>
                    <input
                      class="form-control"
                      type="text"
                      :value="entry.name"
                      @input="updateName"
                      @focus="focusField('name')"
                      @blur="clearFocus"
                    />
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.quantity,
                      'is-focused': activeField == 'quantity'
                    }"
                  >
                    <label class="bmd-label-floating">{{
                      $t('cruds.product.fields.quantity')
                    }}</label>
                    <input
                      class="form-control"
                      type="number"
                      step="1"
                      :value="entry.quantity"
                      @input="updateQuantity"
                      @focus="focusField('quantity')"
                      @blur="clearFocus"
                    />
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.category_id !== null,
                      'is-focused': activeField == 'category'
                    }"
                  >
                    <label class="bmd-label-floating">{{
                      $t('cruds.product.fields.category')
                    }}</label>
                    <v-select
                      name="category"
                      label="name"
                      :key="'category-field'"
                      :value="entry.category_id"
                      :options="lists.category"
                      :reduce="entry => entry.id"
                      @input="updateCategory"
                      @search.focus="focusField('category')"
                      @search.blur="clearFocus"
                    />
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.amount,
                      'is-focused': activeField == 'amount'
                    }"
                  >
                    <label class="bmd-label-floating required">{{
                      $t('cruds.product.fields.amount')
                    }}</label>
                    <input
                      class="form-control"
                      type="number"
                      step="0.01"
                      :value="entry.amount"
                      @input="updateAmount"
                      @focus="focusField('amount')"
                      @blur="clearFocus"
                      required
                    />
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.tag.length !== 0,
                      'is-focused': activeField == 'tag'
                    }"
                  >
                    <label class="bmd-label-floating required">{{
                      $t('cruds.product.fields.tag')
                    }}</label>
                    <v-select
                      name="tag"
                      label="name"
                      :key="'tag-field'"
                      :value="entry.tag"
                      :options="lists.tag"
                      :closeOnSelect="false"
                      multiple
                      @input="updateTag"
                      @search.focus="focusField('tag')"
                      @search.blur="clearFocus"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <vue-button-spinner
                class="btn-primary"
                :status="status"
                :isLoading="loading"
                :disabled="loading"
              >
                {{ $t('global.save') }}
              </vue-button-spinner>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  data() {
    return {
      status: '',
      activeField: ''
    }
  },
  computed: {
    ...mapGetters('ProductsSingle', ['entry', 'loading', 'lists'])
  },
  beforeDestroy() {
    this.resetState()
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler() {
        this.resetState()
        this.fetchEditData(this.$route.params.id)
      }
    }
  },
  methods: {
    ...mapActions('ProductsSingle', [
      'fetchEditData',
      'updateData',
      'resetState',
      'setName',
      'setQuantity',
      'setCategory',
      'setAmount',
      'setTag'
    ]),
    updateName(e) {
      this.setName(e.target.value)
    },
    updateQuantity(e) {
      this.setQuantity(e.target.value)
    },
    updateCategory(value) {
      this.setCategory(value)
    },
    updateAmount(e) {
      this.setAmount(e.target.value)
    },
    updateTag(value) {
      this.setTag(value)
    },
    submitForm() {
      this.updateData()
        .then(() => {
          this.$router.push({ name: 'products.index' })
          this.$eventHub.$emit('update-success')
        })
        .catch(error => {
          this.status = 'failed'
          _.delay(() => {
            this.status = ''
          }, 3000)
        })
    },
    focusField(name) {
      this.activeField = name
    },
    clearFocus() {
      this.activeField = ''
    }
  }
}
</script>
