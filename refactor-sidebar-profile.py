#!/usr/bin/env python3
"""
Refactor Vue components to use new SidebarMenu and ProfilePanel components.
Based on the pattern from SetPraktikan.vue and Soal.vue
"""
import os
import re

# Files to refactor
FILES_TO_REFACTOR = [
    'resources/js/Pages/Asisten.vue',
    'resources/js/Pages/History.vue',
    'resources/js/Pages/Jawaban.vue',
    'resources/js/Pages/Kelas.vue',
    'resources/js/Pages/Konfigurasi.vue',
    'resources/js/Pages/Laporan.vue',
    'resources/js/Pages/Modul.vue',
    'resources/js/Pages/Pelanggaran.vue',
    'resources/js/Pages/Plotting.vue',
    'resources/js/Pages/Polling.vue',
    'resources/js/Pages/Praktikum.vue',
    'resources/js/Pages/Rating.vue',
    'resources/js/Pages/TugasPendahuluan.vue',
]

PAGE_ID_MAP = {
    'Asisten.vue': 'asisten',
    'History.vue': 'history',
    'Jawaban.vue': 'jawaban',
    'Kelas.vue': 'kelas',
    'Konfigurasi.vue': 'konfigurasi',
    'Laporan.vue': 'laporan',
    'Modul.vue': 'modul',
    'Pelanggaran.vue': 'pelanggaran',
    'Plotting.vue': 'plotting',
    'Polling.vue': 'polling',
    'Praktikum.vue': 'praktikum',
    'Rating.vue': 'rating',
    'TugasPendahuluan.vue': 'tp',
}

def backup_file(filepath):
    """Create a backup of the file"""
    backup_path = filepath + '.backup'
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
        with open(backup_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"  📦 Backup created: {backup_path}")

def get_page_id(filename):
    basename = os.path.basename(filename)
    return PAGE_ID_MAP.get(basename, 'unknown')

print("=" * 80)
print("SIDEBAR & PROFILE PANEL REFACTORING")
print("=" * 80)
print("\nThis script will:")
print("1. Replace old sidebar HTML with <SidebarMenu> component")
print("2. Replace old profile panel HTML with <AsistenProfilePanel> component")
print("3. Add necessary imports and setup() function")
print("4. Add useSidebarMenu composable integration")
print("5. Update scroll restoration to use menuRef")
print("\nFiles to refactor:")
for f in FILES_TO_REFACTOR:
    print(f"  - {f}")

print("\n" + "=" * 80)
input("\nPress Enter to continue or Ctrl+C to cancel...")

print("\n🚀 Starting refactoring...\n")

for file_path in FILES_TO_REFACTOR:
    if not os.path.exists(file_path):
        print(f"❌ File not found: {file_path}")
        continue
    
    print(f"\n📝 Processing: {file_path}")
    backup_file(file_path)
    
    page_id = get_page_id(file_path)
    print(f"  📍 Page ID: {page_id}")
    
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Check if already refactored
    if 'SidebarMenu' in content and 'AsistenProfilePanel' in content:
        print(f"  ⏭️  Already refactored, skipping...")
        continue
    
    print(f"  ✍️  Needs manual refactoring")
    print(f"  💡 Follow this pattern:")
    print(f"""
  1. In <template>:
     Replace old sidebar with:
     <SidebarMenu
       :page-active="pageActive"
       :items="visibleMenuItems"
       :menu-container-class="menuContainerClass"
       :highlighted-menu="highlightedMenu"
       :menu-ref="menuRefWrapped.ref"
       @hover="isMenuShown = false"
       @select="handleMenuSelect"
     />
     
     Replace old profile panel with:
     <AsistenProfilePanel
       :page-active="pageActive"
       :is-menu-shown="isMenuShown"
       :current-user="currentUser"
       :user-role="userRole"
       @update:isMenuShown="isMenuShown = $event"
       @sign-out="signOut"
     />
  
  2. In <script>:
     Add imports:
     import {{ MENU_ITEMS, PRIVILEGES }} from '../constants';
     import {{ ref, toRef, toRefs }} from 'vue';
     import {{ useNavigation }} from '@/composables/useNavigation';
     import {{ useToast }} from '@/composables/useToast';
     import {{ useSidebarMenu }} from '@/composables/useSidebarMenu';
     import SidebarMenu from '@/components/asisten/SidebarMenu.vue';
     import AsistenProfilePanel from '@/components/asisten/ProfilePanel.vue';
     
     Add components:
     components: {{
       SidebarMenu,
       AsistenProfilePanel,
     }},
     
     Add setup():
     setup(props) {{
       const menuRef = ref(null);
       
       const navigation = useNavigation({{
         userType: 'asisten',
         menuRef: menuRef,
         currentPage: '{page_id}'
       }});
       const navigationRefs = toRefs(navigation);
       
       navigation.initializeMenu(props.comingFrom, true);
       const toast = useToast();
       
       const sidebarMenu = useSidebarMenu({{
         menuItems: MENU_ITEMS,
         privileges: PRIVILEGES,
         currentUser: toRef(props, 'currentUser'),
         currentPageId: '{page_id}',
         changePage: navigationRefs.changePage,
       }});
       
       return {{
         toast,
         menuRef,
         menuRefWrapped: {{ ref: menuRef }},
         ...navigationRefs,
         ...sidebarMenu,
       }};
     }},
     
  3. In data():
     Remove all menu* properties (menuPraktikum, menuSoal, etc.)
     
  4. In methods:
     Add handleMenuSelect:
     handleMenuSelect(target) {{
       this.setActiveMenu(target);
       this.travel(target);
     }},
     
  5. In mounted():
     Update scroll restoration:
     if (this.menuRef && this.position != null) {{
       this.$nextTick(() => {{
         const target = Number(this.position) || 0;
         if (this.menuRef && this.menuRef.scrollTop !== undefined) {{
           this.menuRef.scrollTop = target;
         }}
       }});
     }}
  """)

print("\n" + "=" * 80)
print("✅ Analysis complete!")
print("\n⚠️  IMPORTANT: This refactoring requires manual changes.")
print("Please refactor each file following the pattern shown above.")
print("You can use SetPraktikan.vue and Soal.vue as references.")
print("=" * 80)

