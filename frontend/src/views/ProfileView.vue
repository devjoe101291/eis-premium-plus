<template>
  <div class="w-full min-h-screen bg-slate-50 overflow-hidden h-screen">
    <div class="flex min-h-screen overflow-hidden h-screen">

      <!-- MAIN -->
      <main class="relative flex-1 overflow-hidden">
        <div class="px-8 py-6 space-y-6 overflow-hidden">
          <div class="mb-4 flex flex-wrap items-center gap-4">
            <div
              class="grid h-12 w-12 place-items-center rounded-xl bg-primary/10 text-primary shadow-sm"
            >
              <UserCircle class="h-6 w-6" />
            </div>
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                Profile
              </h1>
              <p class="text-slate-600 dark:text-slate-400 mt-1">
                Manage your account details and security settings
              </p>
            </div>
          </div>
          <div
            v-if="error"
            class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700"
          >
            {{ error }}
          </div>
          <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
              <!-- LEFT PROFILE -->
              <section class="lg:col-span-5">
                <div class="flex flex-col items-center text-center lg:pt-10">
                  <div class="relative">
                    <div class="h-44 w-44 rounded-full bg-gradient-to-br from-primary to-secondary p-[3px] shadow-lg">
                      <template v-if="hasAvatar">
                        <img
                          :src="user.avatar"
                          alt="profile"
                          class="h-full w-full rounded-full object-cover bg-white"
                        />
                      </template>
                      <div
                        v-else
                        class="flex h-full w-full items-center justify-center rounded-full text-5xl font-semibold text-white"
                        :style="avatarFillStyle"
                        aria-label="Profile initial"
                      >
                        {{ avatarInitial }}
                      </div>
                    </div>

                    <button
                      type="button"
                      class="absolute bottom-2 right-2 grid h-12 w-12 place-items-center rounded-full
                             bg-gradient-to-br from-primary to-secondary text-white shadow-lg hover:brightness-110 transition"
                      title="Edit photo"
                      @click="openAvatarEdit"
                    >
                      <Pencil class="h-5 w-5" />
                    </button>
                  </div>

                  <h1 class="mt-6 text-2xl font-semibold text-slate-900">
                    {{ displayName }}
                  </h1>
                  <p class="mt-1 text-sm text-slate-500">{{ formattedRole }}</p>
                </div>
              </section>

              <!-- RIGHT CARDS -->
              <section class="space-y-6 lg:col-span-7">
                <!-- Personal Details -->
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                  <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                      <span class="grid h-7 w-7 place-items-center rounded-lg bg-primary/10 text-primary">
                        <User class="h-4 w-4" />
                      </span>
                      <h2 class="text-sm font-semibold text-primary">Personal Details</h2>
                    </div>

                    <button
                      type="button"
                      class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-primary to-secondary text-white shadow-sm
                             hover:brightness-110 transition"
                      title="Edit personal details"
                      @click="editing.personal = true"
                    >
                      <Pencil class="h-4 w-4" />
                    </button>
                  </div>

                  <div class="px-6 py-5">
                    <div class="grid grid-cols-1 gap-x-12 gap-y-6 sm:grid-cols-2">
                      <div>
                        <div class="text-xs font-semibold text-slate-700">First Name</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.firstName || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-slate-700">Last Name</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.lastName || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-slate-700">Username</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.username || '-' }}</div>
                      </div>


                      <div>
                        <div class="text-xs font-semibold text-slate-700">Date of Birth</div>
                        <div class="mt-1 text-sm text-slate-600">{{ formattedDateOfBirth }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-slate-700">Gender</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.gender || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-slate-700">Phone Number</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.phone || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-slate-700">Street</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.street || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-slate-700">City</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.city || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-slate-700">State</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.state || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-slate-700">Zip Code</div>
                        <div class="mt-1 text-sm text-slate-600">{{ user.zipcode || '-' }}</div>
                      </div>

                    </div>
                  </div>
                </div>

                <!-- Account & Security -->
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                  <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                      <span class="grid h-7 w-7 place-items-center rounded-lg bg-secondary/10 text-secondary">
                        <ShieldCheck class="h-4 w-4" />
                      </span>
                      <h2 class="text-sm font-semibold text-secondary">Account &amp; Security</h2>
                    </div>
                  </div>

                  <div class="px-6 py-5">
                    <div class="grid grid-cols-1 gap-x-12 gap-y-6 sm:grid-cols-2">
                      <!-- Email -->
                      <div class="flex items-start gap-3">
                        <div class="flex-1">
                          <div class="text-xs font-semibold text-slate-700">Email</div>
                          <div class="mt-1 text-sm text-slate-600">{{ user.email }}</div>
                        </div>

                        <button
                          type="button"
                          class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-primary to-secondary text-white shadow-sm
                                 hover:brightness-110 transition"
                          title="Edit email"
                          @click="editing.email = true"
                        >
                          <Pencil class="h-4 w-4" />
                        </button>
                      </div>

                      <!-- Password -->
                      <div class="flex items-start justify-between gap-3">
                        <div>
                          <div class="text-xs font-semibold text-slate-700">Password</div>
                          <div class="mt-1 text-sm text-slate-600">••••••••</div>
                        </div>

                        <button
                          type="button"
                          class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-primary to-secondary
                                 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:brightness-110 transition"
                          @click="editing.password = true"
                        >
                          <Lock class="mr-2 h-4 w-4" />
                          Change Password
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- <p class="text-xs text-slate-500">
                  Uses Tailwind only. Primary/Secondary are applied via gradient + badges.
                </p> -->
              </section>
            </div>
        </div>

        <!-- MODAL -->
        <Modal v-model="isProfileModalOpen" :title="modalTitle">
          <div class="space-y-4">
            <!-- Personal -->
            <div v-if="editing.personal" class="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">First Name</label>
                <input
                  v-model="draft.firstName"
                  type="text"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Last Name</label>
                <input
                  v-model="draft.lastName"
                  type="text"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Username</label>
                <input
                  v-model="draft.username"
                  type="text"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Gender</label>
                <select
                  v-model="draft.gender"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                >
                  <option value="">Select</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Prefer not to say">Prefer not to say</option>
                </select>
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Phone</label>
                <input
                  v-model="draft.phone"
                  type="text"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Date of Birth</label>
                <input
                  v-model="draft.dateOfBirth"
                  type="date"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Street</label>
                <input
                  v-model="draft.street"
                  type="text"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">City</label>
                <input
                  v-model="draft.city"
                  type="text"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">State</label>
                <select
                  v-model="draft.state"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                >
                  <option value="">Select state</option>
                  <option value="Alabama">Alabama</option>
                  <option value="Alaska">Alaska</option>
                  <option value="Arizona">Arizona</option>
                  <option value="Arkansas">Arkansas</option>
                  <option value="California">California</option>
                  <option value="Colorado">Colorado</option>
                  <option value="Connecticut">Connecticut</option>
                  <option value="Delaware">Delaware</option>
                  <option value="Florida">Florida</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Hawaii">Hawaii</option>
                  <option value="Idaho">Idaho</option>
                  <option value="Illinois">Illinois</option>
                  <option value="Indiana">Indiana</option>
                  <option value="Iowa">Iowa</option>
                  <option value="Kansas">Kansas</option>
                  <option value="Kentucky">Kentucky</option>
                  <option value="Louisiana">Louisiana</option>
                  <option value="Maine">Maine</option>
                  <option value="Maryland">Maryland</option>
                  <option value="Massachusetts">Massachusetts</option>
                  <option value="Michigan">Michigan</option>
                  <option value="Minnesota">Minnesota</option>
                  <option value="Mississippi">Mississippi</option>
                  <option value="Missouri">Missouri</option>
                  <option value="Montana">Montana</option>
                  <option value="Nebraska">Nebraska</option>
                  <option value="Nevada">Nevada</option>
                  <option value="New Hampshire">New Hampshire</option>
                  <option value="New Jersey">New Jersey</option>
                  <option value="New Mexico">New Mexico</option>
                  <option value="New York">New York</option>
                  <option value="North Carolina">North Carolina</option>
                  <option value="North Dakota">North Dakota</option>
                  <option value="Ohio">Ohio</option>
                  <option value="Oklahoma">Oklahoma</option>
                  <option value="Oregon">Oregon</option>
                  <option value="Pennsylvania">Pennsylvania</option>
                  <option value="Rhode Island">Rhode Island</option>
                  <option value="South Carolina">South Carolina</option>
                  <option value="South Dakota">South Dakota</option>
                  <option value="Tennessee">Tennessee</option>
                  <option value="Texas">Texas</option>
                  <option value="Utah">Utah</option>
                  <option value="Vermont">Vermont</option>
                  <option value="Virginia">Virginia</option>
                  <option value="Washington">Washington</option>
                  <option value="West Virginia">West Virginia</option>
                  <option value="Wisconsin">Wisconsin</option>
                  <option value="Wyoming">Wyoming</option>
                </select>
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Zip Code</label>
                <input
                  v-model="draft.zipcode"
                  type="text"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
            </div>

            <!-- Email -->
            <div v-else-if="editing.email">
              <label class="mb-1 block text-xs font-semibold text-slate-700">Email</label>
              <input
                v-model="draft.email"
                type="email"
                class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                       border border-slate-200/60 dark:border-slate-700/60
                       text-slate-800 dark:text-slate-100
                       placeholder:text-slate-400 dark:placeholder:text-slate-500
                       shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                       focus:ring-offset-white dark:focus:ring-offset-slate-900
                       focus:ring-[#ff8e48]/60"
              />
            </div>

            <!-- Password -->
            <div v-else-if="editing.password" class="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <label class="mb-1 block text-xs font-semibold text-slate-700">Current Password</label>
                <input
                  v-model="passwordForm.currentPassword"
                  type="password"
                  placeholder="••••••••"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">New Password</label>
                <input
                  v-model="passwordForm.newPassword"
                  type="password"
                  placeholder="Min 8 characters"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
              <div>
                <label class="mb-1 block text-xs font-semibold text-slate-700">Confirm Password</label>
                <input
                  v-model="passwordForm.confirmPassword"
                  type="password"
                  placeholder="Re-enter password"
                  class="w-full rounded-xl px-4 py-3 text-sm bg-white/70 dark:bg-slate-900/40
                         border border-slate-200/60 dark:border-slate-700/60
                         text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2
                         focus:ring-offset-white dark:focus:ring-offset-slate-900
                         focus:ring-[#ff8e48]/60"
                />
              </div>
            </div>

            <!-- Avatar -->
            <div v-else-if="editing.avatar" class="space-y-4">
              <div class="flex items-center gap-4">
                <template v-if="avatarPreview || hasAvatar">
                  <img
                    :src="avatarPreview || user.avatar"
                    alt="avatar preview"
                    class="h-20 w-20 rounded-full object-cover border border-slate-200"
                  />
                </template>
                <div
                  v-else
                  class="flex h-20 w-20 items-center justify-center rounded-full border border-slate-200 text-xl font-semibold text-white"
                  :style="avatarFillStyle"
                  aria-label="Profile initial"
                >
                  {{ avatarInitial }}
                </div>
                <div class="flex-1">
                  <label class="mb-1 block text-xs font-semibold text-slate-700">Choose Image</label>
                  <input
                    type="file"
                    accept="image/*"
                    class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200"
                    @change="handleAvatarChange"
                  />
                  <p class="mt-1 text-xs text-slate-500">PNG, JPG, or WEBP. Max 2MB.</p>
                </div>
              </div>
            </div>

            <div v-else class="text-sm text-slate-600">
              Select a section to edit.
            </div>
          </div>

          <template #footer>
            <button
              type="button"
              class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
              @click="closeAll"
            >
              Cancel
            </button>
            <button
              type="button"
              class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-primary to-secondary px-4 py-2 text-sm font-semibold text-white
                     shadow-sm hover:brightness-110 transition disabled:cursor-not-allowed disabled:opacity-60"
              :disabled="loading"
              @click="save"
            >
              Save
            </button>
          </template>
        </Modal>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, reactive, ref, onMounted, onBeforeUnmount } from 'vue'
import { UserCircle, User, ShieldCheck, Pencil, Lock } from 'lucide-vue-next'
import api from '@/services/api'
import { useAuthStore } from '@/stores/auth'
import { getAvatarColors } from '@/utils/avatarColor'
import Modal from '@/components/layout/Modal.vue'

interface Profile {
  id: number
  name?: string
  first_name?: string
  last_name?: string
  email: string
  username?: string
  role?: string
  status?: string
  phone?: string
  address?: string
  street?: string
  city?: string
  state?: string
  zipcode?: string
  date_of_birth?: string
  dob?: string
  gender?: string
  profile_picture?: string
  email_verified_at?: string
  created_at?: string
  updated_at?: string
}

interface ProfileResponse {
  success?: boolean
  data: Profile
}

const authStore = useAuthStore()

const user = reactive({
  id: 0,
  name: '',
  firstName: '',
  lastName: '',
  username: '',
  role: '',
  status: '',
  dateOfBirth: '',
  gender: '',
  address: '',
  street: '',
  city: '',
  state: '',
  zipcode: '',
  phone: '',
  email: '',
  emailVerifiedAt: '',
  createdAt: '',
  updatedAt: '',
  avatar: ''
})

const loading = ref(false)
const error = ref('')
const avatarFile = ref<File | null>(null)
const avatarPreview = ref('')
const previousBodyOverflow = ref<string | null>(null)
const previousHtmlOverflow = ref<string | null>(null)

const editing = reactive({
  personal: false,
  email: false,
  password: false,
  avatar: false
})

const passwordForm = reactive({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
})

const draft = reactive({
  firstName: '',
  lastName: '',
  username: '',
  dateOfBirth: '',
  gender: '',
  street: '',
  city: '',
  state: '',
  zipcode: '',
  phone: '',
  email: ''
})

const anyModalOpen = computed(() =>
  editing.personal || editing.email || editing.password || editing.avatar
)

const hasAvatar = computed(() => Boolean(user.avatar))

const avatarInitial = computed(() => {
  const raw = user.firstName || user.lastName || user.username || user.name || user.email
  const initial = raw.trim().charAt(0).toUpperCase()
  return initial
})

const displayName = computed(() => {
  const combined = [user.firstName, user.lastName].filter(Boolean).join(' ')
  return combined || user.name || '-'
})


const avatarSeed = computed(() =>
  String(user.id || user.email || user.username || user.firstName || user.lastName || 'user')
)

const avatarColors = computed(() => getAvatarColors(avatarSeed.value))

const avatarFillStyle = computed(() => ({
  backgroundColor: avatarColors.value.primary
}))

const modalTitle = computed(() => {
  if (editing.personal) return 'Edit Personal Details'
  if (editing.email) return 'Edit Email'
  if (editing.password) return 'Change Password'
  if (editing.avatar) return 'Edit Profile Photo'
  return 'Edit'
})

const formattedDateOfBirth = computed(() => {
  if (!user.dateOfBirth) return '-'
  const date = new Date(user.dateOfBirth)
  if (Number.isNaN(date.getTime())) return '-'
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const formattedRole = computed(() => {
  const raw = (user.role || '').trim()
  if (!raw) return '-'
  return raw.charAt(0).toUpperCase() + raw.slice(1)
})

const closeAll = () => {
  editing.personal = false
  editing.email = false
  editing.password = false
  editing.avatar = false
  passwordForm.currentPassword = ''
  passwordForm.newPassword = ''
  passwordForm.confirmPassword = ''
  if (avatarPreview.value) {
    URL.revokeObjectURL(avatarPreview.value)
    avatarPreview.value = ''
  }
}

const isProfileModalOpen = computed({
  get: () => anyModalOpen.value,
  set: (value) => {
    if (!value) closeAll()
  }
})

const openAvatarEdit = () => {
  editing.avatar = true
  avatarFile.value = null
  if (avatarPreview.value) {
    URL.revokeObjectURL(avatarPreview.value)
  }
  avatarPreview.value = ''
  error.value = ''
}

const splitName = (name: string) => {
  const parts = name.trim().split(/\s+/).filter(Boolean)
  return {
    firstName: parts[0] ?? '',
    lastName: parts.slice(1).join(' ')
  }
}

const getProfileName = (profile: Profile) => {
  const combined = [profile.first_name, profile.last_name].filter(Boolean).join(' ')
  return profile.name || combined
}

const parseAddress = (address?: string) => {
  const empty = { street: '', city: '', state: '', zipcode: '' }
  if (!address) return empty

  const parts = address
    .split(',')
    .map(part => part.trim())
    .filter(Boolean)

  if (!parts.length) return empty

  const street = parts[0] ?? ''
  const city = parts[1] ?? ''
  const stateZip = parts.slice(2).join(' ')
  const stateZipParts = stateZip.split(/\s+/).filter(Boolean)
  const state = stateZipParts.shift() ?? ''
  const zipcode = stateZipParts.join(' ')

  return { street, city, state, zipcode }
}

const normalizeGender = (value?: string) => {
  const raw = (value || '').trim().toLowerCase()
  if (!raw) return ''
  if (raw === 'male' || raw === 'm') return 'Male'
  if (raw === 'female' || raw === 'f') return 'Female'
  if (raw === 'other') return 'Other'
  if (raw === 'prefer not to say' || raw === 'prefer_not_to_say' || raw === 'prefer-not-to-say') {
    return 'Prefer not to say'
  }
  // fallback: title case
  return raw.replace(/\b\w/g, (c) => c.toUpperCase())
}

const normalizeDateInput = (value?: string) => {
  const raw = (value || '').trim()
  if (!raw) return ''
  if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) return raw
  const date = new Date(raw)
  if (Number.isNaN(date.getTime())) return ''
  const year = date.getUTCFullYear()
  const month = String(date.getUTCMonth() + 1).padStart(2, '0')
  const day = String(date.getUTCDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}

const normalizeAddress = (profile: Profile) => {
  if (profile.street || profile.city || profile.state || profile.zipcode) {
    return {
      street: profile.street ?? '',
      city: profile.city ?? '',
      state: profile.state ?? '',
      zipcode: profile.zipcode ?? ''
    }
  }

  return parseAddress(profile.address)
}

const buildAddress = (street: string, city: string, state: string, zipcode: string) => {
  const tail = [state, zipcode].filter(Boolean).join(' ')
  return [street, city, tail].filter(Boolean).join(', ')
}

const syncUserFromProfile = (profile: Profile) => {
  const baseName = getProfileName(profile) ?? ''
  const { firstName, lastName } = splitName(baseName)
  const address = normalizeAddress(profile)
  user.id = profile.id ?? 0
  user.name = baseName
  user.firstName = firstName
  user.lastName = lastName
  user.username = profile.username ?? (profile.email?.split('@')[0] || '')
  user.role = profile.role ?? 'User'
  user.status = profile.status ?? ''
  const nextDateOfBirth = profile.date_of_birth ?? profile.dob
  user.dateOfBirth = nextDateOfBirth !== undefined ? nextDateOfBirth : user.dateOfBirth
  const nextGender = profile.gender
  user.gender = nextGender !== undefined ? normalizeGender(nextGender) : user.gender
  user.address = profile.address ?? buildAddress(address.street, address.city, address.state, address.zipcode)
  user.street = address.street
  user.city = address.city
  user.state = address.state
  user.zipcode = address.zipcode
  user.phone = profile.phone ?? ''
  user.email = profile.email ?? ''
  user.emailVerifiedAt = profile.email_verified_at ?? ''
  user.createdAt = profile.created_at ?? ''
  user.updatedAt = profile.updated_at ?? ''
  user.avatar = profile.profile_picture || ''

  draft.firstName = user.firstName
  draft.lastName = user.lastName
  draft.username = user.username
  draft.dateOfBirth = normalizeDateInput(user.dateOfBirth)
  draft.gender = user.gender
  draft.street = user.street
  draft.city = user.city
  draft.state = user.state
  draft.zipcode = user.zipcode
  draft.phone = user.phone
  draft.email = user.email
}

const updateAuthCache = (profile: Profile) => {
  const display = getProfileName(profile)
  authStore.user = {
    id: profile.id,
    name: display,
    email: profile.email,
    role: profile.role
  }

  const storage = sessionStorage.getItem('token')
    ? sessionStorage
    : localStorage.getItem('token')
      ? localStorage
      : null

  if (storage) {
    storage.setItem('user', JSON.stringify(authStore.user))
  }
}

const fetchProfile = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await api.get<ProfileResponse | Profile>('/profile')
    const data = (response.data as ProfileResponse).data ?? (response.data as Profile)
    syncUserFromProfile(data)
    updateAuthCache(data)
  } catch (err) {
    console.error('Profile fetch error:', err)
    error.value = 'Failed to load profile'
  } finally {
    loading.value = false
  }
}

const save = async () => {
  if (editing.avatar) {
    if (!avatarFile.value) {
      error.value = 'Please choose an image to upload'
      return
    }

    const formData = new FormData()
    formData.append('avatar', avatarFile.value)

    loading.value = true
    error.value = ''
    try {
      const response = await api.post<ProfileResponse | Profile>('/profile/avatar', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      const data = (response.data as ProfileResponse).data ?? (response.data as Profile)
      syncUserFromProfile(data)
      updateAuthCache(data)
      closeAll()
    } catch (err) {
      console.error('Avatar upload error:', err)
      error.value = 'Failed to upload profile picture'
    } finally {
      loading.value = false
    }
    return
  }

  if (editing.password) {
    if (!passwordForm.currentPassword || !passwordForm.newPassword || !passwordForm.confirmPassword) {
      error.value = 'Please complete all password fields'
      return
    }
    if (passwordForm.newPassword.length < 8) {
      error.value = 'New password must be at least 8 characters'
      return
    }
    if (passwordForm.newPassword !== passwordForm.confirmPassword) {
      error.value = 'Passwords do not match'
      return
    }
    if (passwordForm.newPassword === passwordForm.currentPassword) {
      error.value = 'New password must be different from current password'
      return
    }

    loading.value = true
    error.value = ''
    try {
      await api.post('/profile/password', {
        current_password: passwordForm.currentPassword,
        password: passwordForm.newPassword,
        password_confirmation: passwordForm.confirmPassword
      })
      closeAll()
    } catch (err) {
      const message =
        (err as { response?: { data?: { message?: string } } })?.response?.data?.message ||
        'Failed to change password'
      error.value = message
    } finally {
      loading.value = false
    }
    return
  }

  const payload: Partial<Profile> = {}

  if (editing.personal) {
    payload.first_name = draft.firstName
    payload.last_name = draft.lastName
    payload.username = draft.username
    payload.address = buildAddress(draft.street, draft.city, draft.state, draft.zipcode)
    payload.date_of_birth = draft.dateOfBirth
    payload.gender = draft.gender
    payload.phone = draft.phone
  }
  if (editing.email) {
    payload.email = draft.email
  }

  if (!Object.keys(payload).length) {
    closeAll()
    return
  }

  loading.value = true
  error.value = ''
  try {
    const response = await api.put<ProfileResponse | Profile>('/profile', payload)
    const data = (response.data as ProfileResponse).data ?? (response.data as Profile)
    syncUserFromProfile(data)
    updateAuthCache(data)
    closeAll()
  } catch (err) {
    console.error('Profile update error:', err)
    error.value = 'Failed to update profile'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProfile()
  previousBodyOverflow.value = document.body.style.overflow
  previousHtmlOverflow.value = document.documentElement.style.overflow
  document.body.style.overflow = 'hidden'
  document.documentElement.style.overflow = 'hidden'
})

onBeforeUnmount(() => {
  document.body.style.overflow = previousBodyOverflow.value ?? ''
  document.documentElement.style.overflow = previousHtmlOverflow.value ?? ''
})

const handleAvatarChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0] ?? null
  avatarFile.value = file
  if (avatarPreview.value) {
    URL.revokeObjectURL(avatarPreview.value)
  }
  avatarPreview.value = file ? URL.createObjectURL(file) : ''
}

</script>
