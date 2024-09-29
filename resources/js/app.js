import './bootstrap'

function addCityInput ()
{
    const input = document.createElement('input')
    input.type = 'text'
    input.name = 'cities[]'
    input.placeholder = 'Add city'
    
    input.style.marginTop = '5px'
    input.style.display = 'block'

    document.getElementById('city-inputs')
        .appendChild(input)
}
