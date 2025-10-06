import { computed, ref, unref } from 'vue';

/**
 * Shared sidebar menu composable for asisten pages.
 * Handles privilege filtering, highlight states, and menu container classes.
 */
export function useSidebarMenu(options = {}) {
  const {
    menuItems = [],
    privileges = {},
    currentUser = null,
    currentPageId = '',
    changePage = null,
  } = options;

  const activeMenu = ref(null);

  const resolvePrivileges = () => unref(privileges) || {};
  const resolveCurrentUser = () => unref(currentUser) || {};
  const resolveCurrentPageId = () => unref(currentPageId) || '';

  const hasMenuAccess = item => {
    if (!item.privilege) {
      return true;
    }

    const privilege = resolvePrivileges()[item.privilege];
    if (!privilege) {
      return false;
    }

    if (privilege === 'all') {
      return true;
    }

    const user = resolveCurrentUser();
    return Array.isArray(privilege) && privilege.includes(user.role_id);
  };

  const visibleMenuItems = computed(() => {
    const currentId = resolveCurrentPageId();

    return (unref(menuItems) || [])
      .map(item => ({
        ...item,
        isCurrentPage: item.id === currentId,
      }))
      .filter(item => hasMenuAccess(item));
  });

  const highlightedMenu = computed(() => {
    const isChangingPage = changePage ? unref(changePage) : false;
    const currentId = resolveCurrentPageId();

    if (isChangingPage && activeMenu.value) {
      return activeMenu.value;
    }

    return currentId;
  });

  const menuContainerClass = computed(() => {
    const isNavigating = changePage ? unref(changePage) : false;
    const highlight = highlightedMenu.value;
    const navigatingToProfile = isNavigating && highlight === 'asisten';

    return navigatingToProfile ? 'rounded-none' : 'rounded-tl-large';
  });

  const setActiveMenu = target => {
    activeMenu.value = target;
  };

  return {
    activeMenu,
    highlightedMenu,
    visibleMenuItems,
    menuContainerClass,
    hasMenuAccess,
    setActiveMenu,
  };
}
