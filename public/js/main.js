// Конфигурация API
const API_CONFIG = {
  ENDPOINT: 'https://localhost:4443/paste/save',
  HEADERS: {
    'X-Requested-With': 'XMLHttpRequest'
  }
};

// Элементы UI
const UI_ELEMENTS = {
  form: document.querySelector('form[name="paste"]'),
  submitButton: document.querySelector('form[name="paste"] button[type="submit"]')
};

// Обработчики событий
const setupEventListeners = () => {
  if (UI_ELEMENTS.form) {
    UI_ELEMENTS.form.addEventListener('submit', handleFormSubmit);
  }
};

// Основной обработчик формы
const handleFormSubmit = async (event) => {
  event.preventDefault();

  try {
    disableSubmitButton(true);
    const formData = new FormData(event.target);
    const response = await submitFormData(formData);

    if (response.ok) {
      const data = await parseResponse(response);
      handleSuccessResponse(data);
    } else {
      handleHttpError(response);
    }
  } catch (error) {
    handleNetworkError(error);
  } finally {
    disableSubmitButton(false);
  }
};

// Отправка данных формы
const submitFormData = async (formData) => {
  return fetch(API_CONFIG.ENDPOINT, {
    method: 'POST',
    body: formData,
    headers: API_CONFIG.HEADERS
  });
};

// Парсинг ответа сервера
const parseResponse = async (response) => {
  const contentType = response.headers.get('content-type');

  if (contentType && contentType.includes('application/json')) {
    return response.json();
  }
  throw new Error('Неверный формат ответа сервера');
};

// Обработка успешного ответа
const handleSuccessResponse = (data) => {
  console.log('Успешный ответ:', data);
  showUserNotification('Форма успешно отправлена!', 'success');
  // Дополнительные действия при успехе
};

// Обработка HTTP ошибок
const handleHttpError = async (response) => {
  console.error('HTTP Error:', response.status, response.statusText);
  let errorMessage = `Ошибка ${response.status}: ${response.statusText}`;

  try {
    const errorData = await response.json();
    errorMessage = errorData.message || errorMessage;
  } catch {
    // Не удалось распарсить JSON
  }

  showUserNotification(errorMessage, 'error');
};

// Обработка сетевых ошибок
const handleNetworkError = (error) => {
  console.error('Сетевая ошибка:', error);
  showUserNotification('Ошибка подключения к серверу', 'error');
};

// Управление состоянием кнопки отправки
const disableSubmitButton = (disabled) => {
  if (UI_ELEMENTS.submitButton) {
    UI_ELEMENTS.submitButton.disabled = disabled;
    UI_ELEMENTS.submitButton.textContent = disabled
      ? 'Отправка...'
      : 'Отправить';
  }
};

// Показ уведомлений пользователю
const showUserNotification = (message, type = 'info') => {
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.textContent = message;

  document.body.appendChild(notification);

  setTimeout(() => {
    notification.remove();
  }, 5000);
};

// Инициализация
const init = () => {
  setupEventListeners();
};

// Запуск приложения
init();
