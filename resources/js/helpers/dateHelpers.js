import { formatDistanceToNow, parseISO, format } from 'date-fns'

export function formatCommentDate(dateString) {
  const date = parseISO(dateString)
  const now = new Date()
  const diffInHours = (now - date) / (1000 * 60 * 60)

  if (diffInHours < 24) {
    return formatDistanceToNow(date, { addSuffix: true })
  } else if (diffInHours < 48) {
    return 'yesterday'
  } else {
    return format(date, 'MMM d, yyyy')
  }
}