import { ref, computed } from 'vue';
import { useToast } from '@/composables/useToast';

export function useSoalManagement(initialData) {
  const toast = useToast();
  const editing = ref(false);
  const activeEditId = ref(null);

  const listAllTP = ref(initialData.allTP ?? []);
  const listAllTA = ref(initialData.allTA ?? []);
  const listAllTK = ref(initialData.allTK ?? []);
  const listAllJurnal = ref(initialData.allJurnal ?? []);
  const listAllMandiri = ref(initialData.allMandiri ?? []);
  const listAllFITB = ref(initialData.allFITB ?? []);

  const formTATK = ref({
    id: '', oldModul_id: '', modul_id: '',
    oldPertanyaan: '', pertanyaan: '',
    jawaban_benar: '', jawaban_salah1: '', jawaban_salah2: '', jawaban_salah3: ''
  });

  const formJMFITB = ref({
    id: '', oldSoal: '', soal: '',
    oldModul_id: '', modul_id: '', isSulit: false
  });

  const formTP = ref({
    id: '', jenisSoal: '', oldModul_id: '', modul_id: '',
    oldSoal: '', soal: '', isEssay: false, isProgram: false
  });

  const getTypeConfig = (type) => {
    // Configuration object for each type
    // ... (same as original)
  };

  const resetEditing = () => {
    editing.value = false;
    activeEditId.value = null;
  };

  const editSoal = (soal, isEditing, type) => {
    if (isEditing) {
      editing.value = true;
      activeEditId.value = soal.id;
      // Populate form based on type
    } else {
      resetEditing();
    }
  };

  const createSoal = async (type, axios) => {
    const config = getTypeConfig(type);
    // Create logic...
  };

  const updateSoal = async (type, axios) => {
    const config = getTypeConfig(type);
    // Update logic...
  };

  const deleteSoal = async (id, type, axios) => {
    const config = getTypeConfig(type);
    // Delete logic...
  };

  return {
    listAllTP, listAllTA, listAllTK, listAllJurnal, listAllMandiri, listAllFITB,
    formTATK, formJMFITB, formTP,
    editing, activeEditId,
    editSoal, createSoal, updateSoal, deleteSoal, resetEditing
  };
}