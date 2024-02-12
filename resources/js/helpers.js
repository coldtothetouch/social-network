export const isImage = (file) => {
    let mime = file.mime || file.type
    return mime.split('/')[0].toLowerCase() === 'image'
}
