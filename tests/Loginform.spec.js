import { mount } from '@vue/test-utils'
import Login from '@/Pages/Auth/Login.vue'
import { describe, it, expect } from 'vitest'

describe('Login.vue', () => {
    it('renders login form', () => {
        const wrapper = mount(Login)
        expect(wrapper.text()).toContain('Email')
        expect(wrapper.text()).toContain('Password')
    })

    it('has email and password input fields', () => {
        const wrapper = mount(Login)
        expect(wrapper.find('input[type="email"]').exists()).toBe(true)
        expect(wrapper.find('input[type="password"]').exists()).toBe(true)
    })
})
