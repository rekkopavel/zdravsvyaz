document.querySelector('form[name="paste"]').addEventListener('submit', async function (event) {
  event.preventDefault(); // Останавливаем стандартное поведение отправки формы

  // Получаем данные из формы
  const form = event.target;
  const formData = new FormData(form);

  try {
    // Отправляем запрос
    const response = await fetch('https://localhost:4443/paste/save', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest' // Указывает, что это AJAX-запрос
      }
    });

    if (response.ok) {
      const result = await response.json(); // Предполагаем, что сервер возвращает JSON
      console.log('Успешно:', result);
      alert('Форма успешно отправлена!');
    } else {
      console.error('Ошибка при отправке:', response.status, response.statusText);
      alert('Ошибка при отправке формы.');
    }
  } catch (error) {
    console.error('Сетевая ошибка:', error);
    alert('Ошибка при подключении к серверу.');
  }
});
